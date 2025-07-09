#!/bin/bash

# Logopedics Application - Complete Installation Script
# This script handles all installation scenarios from one-click to manual setup

set -e  # Exit on any error

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
PURPLE='\033[0;35m'
NC='\033[0m' # No Color

# Function to print colored output
print_status() {
    echo -e "${BLUE}[INFO]${NC} $1"
}

print_success() {
    echo -e "${GREEN}[SUCCESS]${NC} $1"
}

print_warning() {
    echo -e "${YELLOW}[WARNING]${NC} $1"
}

print_error() {
    echo -e "${RED}[ERROR]${NC} $1"
}

print_header() {
    echo -e "${PURPLE}$1${NC}"
}

# Function to check if command exists
command_exists() {
    command -v "$1" >/dev/null 2>&1
}

# Function to check Docker connectivity
check_docker_connectivity() {
    print_status "Checking Docker connectivity..."
    if ! docker info >/dev/null 2>&1; then
        print_error "Docker is not running or not accessible"
        exit 1
    fi
    
    # Test Docker Hub connectivity
    if ! docker pull hello-world:latest >/dev/null 2>&1; then
        print_warning "Unable to pull images from Docker Hub. This might be due to network issues."
        print_warning "Please check your internet connection and try again."
        exit 1
    fi
    
    print_success "Docker connectivity confirmed"
}

# Function to clean up Docker containers
cleanup_docker_containers() {
    print_status "Cleaning up Docker containers..."
    
    # Stop and remove any existing containers with our project name
    docker-compose -f docker-compose.simple.yml down --remove-orphans 2>/dev/null || true
    
    # Remove any containers with our project name that might be stuck
    docker ps -a --filter "name=logopedics" --format "{{.ID}}" | xargs -r docker rm -f 2>/dev/null || true
    
    # Clean up any dangling containers
    docker container prune -f 2>/dev/null || true
    
    print_success "Docker containers cleaned up"
}

# Function to check macOS version
check_macos_version() {
    local version=$(sw_vers -productVersion)
    local major=$(echo $version | cut -d. -f1)
    local minor=$(echo $version | cut -d. -f2)

    if [ "$major" -lt 10 ] || ([ "$major" -eq 10 ] && [ "$minor" -lt 15 ]); then
        print_error "macOS 10.15 (Catalina) or later is required. You have macOS $version"
        exit 1
    fi

    print_success "macOS version $version is supported"
}

# Function to check available disk space
check_disk_space() {
    local available_space=$(df -h . | awk 'NR==2 {print $4}' | tr -d 'Gi')
    local required_space=5  # 5GB minimum
    
    if [ "$available_space" -lt "$required_space" ]; then
        print_warning "Low disk space detected. You have ${available_space}GB available, but ${required_space}GB is recommended"
        read -p "Continue anyway? (y/N): " -n 1 -r
        echo
        if [[ ! $REPLY =~ ^[Yy]$ ]]; then
            exit 1
        fi
    else
        print_success "Sufficient disk space available (${available_space}GB)"
    fi
}

# Function to install Homebrew
install_homebrew() {
    if command_exists brew; then
        print_success "Homebrew is already installed"
        return
    fi

    print_status "Installing Homebrew..."
    /bin/bash -c "$(curl -fsSL https://raw.githubusercontent.com/Homebrew/install/HEAD/install.sh)"

    # Add Homebrew to PATH for Apple Silicon Macs
    if [[ $(uname -m) == 'arm64' ]]; then
        echo 'eval "$(/opt/homebrew/bin/brew shellenv)"' >> ~/.zprofile
        eval "$(/opt/homebrew/bin/brew shellenv)"
    fi

    print_success "Homebrew installed successfully"
}

# Function to install Git
install_git() {
    if command_exists git; then
        print_success "Git is already installed"
        return
    fi

    print_status "Installing Git..."
    brew install git

    # Configure Git with basic settings
    if [ -z "$(git config --global user.name)" ]; then
        print_warning "Git user name not configured. Please run:"
        echo "git config --global user.name 'Your Name'"
        echo "git config --global user.email 'your.email@example.com'"
    fi

    print_success "Git installed successfully"
}

# Function to install Docker Desktop
install_docker() {
    if command_exists docker && docker info >/dev/null 2>&1; then
        print_success "Docker is already installed and running"
        return
    fi

    if command_exists docker; then
        print_warning "Docker is installed but not running. Starting Docker Desktop..."
        open -a Docker
        print_status "Waiting for Docker to start..."
        sleep 30

        # Wait for Docker to be ready
        local attempts=0
        while ! docker info >/dev/null 2>&1 && [ $attempts -lt 60 ]; do
            sleep 5
            attempts=$((attempts + 1))
        done

        if docker info >/dev/null 2>&1; then
            print_success "Docker is now running"
        else
            print_error "Docker failed to start. Please start Docker Desktop manually"
            exit 1
        fi
        return
    fi

    print_status "Installing Docker Desktop..."

    # Check if we're on Apple Silicon or Intel
    if [[ $(uname -m) == 'arm64' ]]; then
        # Apple Silicon
        brew install --cask docker
    else
        # Intel Mac
        brew install --cask docker
    fi

    print_status "Starting Docker Desktop..."
    open -a Docker

    print_status "Waiting for Docker to start (this may take a few minutes)..."
    sleep 60

    # Wait for Docker to be ready
    local attempts=0
    while ! docker info >/dev/null 2>&1 && [ $attempts -lt 60 ]; do
        sleep 5
        attempts=$((attempts + 1))
    done

    if docker info >/dev/null 2>&1; then
        print_success "Docker Desktop installed and running"
    else
        print_error "Docker failed to start. Please start Docker Desktop manually and run this script again"
        exit 1
    fi
}

# Function to install additional tools
install_additional_tools() {
    print_status "Installing additional useful tools..."

    # Install jq for JSON processing
    if ! command_exists jq; then
        brew install jq
    fi

    # Install tree for directory visualization
    if ! command_exists tree; then
        brew install tree
    fi

    # Install wget
    if ! command_exists wget; then
        brew install wget
    fi

    print_success "Additional tools installed"
}

# Function to check prerequisites
check_prerequisites() {
    print_status "Checking prerequisites..."

    # Check Docker
    if ! command_exists docker; then
        print_error "Docker is not installed. Will install it automatically."
        return 1
    fi

    if ! docker info >/dev/null 2>&1; then
        print_error "Docker is not running. Will start it automatically."
        return 1
    fi

    # Check Git
    if ! command_exists git; then
        print_error "Git is not installed. Will install it automatically."
        return 1
    fi

    print_success "All prerequisites are satisfied"
    return 0
}

# Function to setup the application
setup_application() {
    print_status "Setting up Logopedics application..."

    # Check if we're in the right directory
    if [ ! -f "composer.json" ]; then
        print_error "composer.json not found. Please run this script from the project root directory"
        exit 1
    fi

    # Create .env file if it doesn't exist
    if [ ! -f ".env" ]; then
        print_status "Creating .env file..."
        cp env.example .env
        print_success ".env file created"
    else
        print_success ".env file already exists"
    fi

        # Install PHP dependencies using Composer container
    print_status "Installing PHP dependencies..."
    print_status "Pulling Composer image (this may take a moment)..."
    docker pull composer:latest
    docker run --rm -v $(pwd):/app -w /app composer:latest composer install --no-dev --optimize-autoloader --ignore-platform-reqs
    print_success "PHP dependencies installed"
    
    # Install Node.js dependencies and build assets
    print_status "Installing Node.js dependencies..."
    print_status "Pulling Node.js image (this may take a moment)..."
    docker pull node:18-alpine || docker pull node:18
    docker run --rm -v $(pwd):/app -w /app node:18-alpine sh -c "npm ci && npm run build" || \
    docker run --rm -v $(pwd):/app -w /app node:18 sh -c "npm ci && npm run build"
    print_success "Node.js dependencies and assets built"
    
    # Set proper permissions
    print_status "Setting file permissions..."
    chmod -R 755 storage bootstrap/cache
    print_success "Permissions set"

    # Generate application key if not set
    if ! grep -q "APP_KEY=base64:" .env; then
        print_status "Generating application key..."
        # Use a simple approach - generate a random key manually
        APP_KEY="base64:$(openssl rand -base64 32)"
        # Use grep and echo to replace the line
        grep -v "^APP_KEY=" .env > .env.tmp
        echo "APP_KEY=$APP_KEY" >> .env.tmp
        mv .env.tmp .env
        print_success "Application key generated"
    else
        print_success "Application key already exists"
    fi

    # Clean up any existing containers to avoid conflicts
    cleanup_docker_containers
    
    # Start the application
    print_status "Starting the application..."
    docker-compose -f docker-compose.simple.yml up -d

    # Wait for services to be ready
    print_status "Waiting for services to start (this may take a few minutes)..."
    sleep 60

    # Check if containers are running
    if docker-compose -f docker-compose.simple.yml ps | grep -q "Up"; then
        print_success "Application containers are running"
    else
        print_error "Some containers failed to start. Checking logs..."
        docker-compose -f docker-compose.simple.yml logs
        exit 1
    fi

    # Run migrations and seed data
    print_status "Setting up database..."
    docker-compose -f docker-compose.simple.yml exec -T app php artisan migrate --force
    docker-compose -f docker-compose.simple.yml exec -T app php artisan db:seed --force

    # Import search data
    print_status "Setting up search functionality..."
    docker-compose -f docker-compose.simple.yml exec -T app php artisan scout:import --force

    print_success "Application setup completed!"
}

# Function to display final instructions
show_final_instructions() {
    echo ""
    print_header "🎉 Installation Complete!"
    echo "========================"
    echo ""
    echo "🌐 Access your application:"
    echo "   Main application: http://localhost:8000"
    echo "   Mail testing tool: http://localhost:8025"
    echo ""
    echo "📋 Useful commands:"
    echo "   Start app: docker-compose -f docker-compose.simple.yml up -d"
    echo "   Stop app:  docker-compose -f docker-compose.simple.yml down"
    echo "   View logs: docker-compose -f docker-compose.simple.yml logs -f"
    echo "   Restart:   docker-compose -f docker-compose.simple.yml restart"
    echo ""
    echo "🔧 Troubleshooting:"
    echo "   If the app doesn't load, wait a few more minutes for all services to start"
    echo "   Check logs: docker-compose -f docker-compose.simple.yml logs"
    echo "   Restart Docker Desktop if you encounter issues"
    echo ""
    echo "📚 For more help, see INSTALL.md"
    echo ""
}

# Function to show installation options
show_installation_options() {
    echo ""
    print_header "🚀 Logopedics Application Installation"
    echo "============================================="
    echo ""
    echo "Choose your installation method:"
    echo ""
    echo "1) One-Click Installation (Easiest)"
    echo "   - Downloads and installs everything automatically"
    echo "   - Perfect for complete beginners"
    echo ""
    echo "2) Quick Start (For users with Docker & Git)"
    echo "   - Fast setup if you already have Docker and Git"
    echo "   - Skips dependency installation"
    echo ""
    echo "3) Complete Installation (Recommended)"
    echo "   - Installs all dependencies automatically"
    echo "   - Includes detailed feedback and error handling"
    echo ""
    echo "4) Manual Installation"
    echo "   - Step-by-step instructions"
    echo "   - For advanced users"
    echo ""
}

# Function to handle one-click installation
one_click_installation() {
    print_header "🚀 One-Click Installation"
    echo "============================="
    echo ""

    # Check if we're on macOS
    if [[ "$OSTYPE" != "darwin"* ]]; then
        print_error "This script is designed for macOS only."
        echo "   Please use the manual installation instructions in SETUP.md"
        exit 1
    fi

    # Check if we're already in a logopedics directory
    if [ -f "composer.json" ] && [ -d ".git" ]; then
        print_status "Already in Logopedics project directory, proceeding with installation..."
    else
        # Check if logopedics directory already exists
        if [ -d "logopedics" ]; then
            print_warning "logopedics directory already exists. Removing it..."
            rm -rf logopedics
        fi
        
        print_status "Creating logopedics directory and downloading application..."
        git clone https://github.com/Armagnac1/logopedics.git logopedics
        cd logopedics
    fi

    print_status "Running complete installation..."
    complete_installation

    echo ""
    print_success "Installation complete! Your application is ready at:"
    echo "   http://localhost:8000"
    echo ""
    echo "📁 Application installed in: $(pwd)"
}

# Function to handle quick start installation
quick_start_installation() {
    print_header "⚡ Quick Start Installation"
    echo "==============================="
    echo ""
    
    if check_prerequisites; then
        check_docker_connectivity
        setup_application
        show_final_instructions
    else
        print_error "Prerequisites not met. Please use the complete installation option."
        exit 1
    fi
}

# Function to handle complete installation
complete_installation() {
    print_header "🔧 Complete Installation"
    echo "============================"
    echo ""

        # Check if running as root
    if [ "$EUID" -eq 0 ]; then
        print_error "Please don't run this script as root"
        exit 1
    fi
    
    # Check macOS version
    check_macos_version
    
    # Check disk space
    check_disk_space
    
    # Check Docker connectivity
    check_docker_connectivity

    # Install dependencies
    install_homebrew
    install_git
    install_docker
    install_additional_tools

    # Setup application
    setup_application

    # Show final instructions
    show_final_instructions
}

# Function to show manual installation instructions
manual_installation() {
    print_header "📖 Manual Installation Instructions"
    echo "========================================="
    echo ""
    echo "For manual installation, please follow the instructions in SETUP.md"
    echo ""
    echo "Quick reference:"
    echo "1. Install Homebrew: /bin/bash -c \"\$(curl -fsSL https://raw.githubusercontent.com/Homebrew/install/HEAD/install.sh)\""
    echo "2. Install Git: brew install git"
    echo "3. Install Docker: brew install --cask docker"
    echo "4. Clone repository: git clone https://github.com/Armagnac1/logopedics.git"
    echo "5. Follow the setup instructions in SETUP.md"
    echo ""
}

# Main installation process
main() {
    # Check if script is being run directly or piped
    if [ -t 0 ]; then
        # Interactive mode
        show_installation_options

        read -p "Enter your choice (1-4): " choice
        echo ""

        case $choice in
            1)
                one_click_installation
                ;;
            2)
                quick_start_installation
                ;;
            3)
                complete_installation
                ;;
            4)
                manual_installation
                ;;
            *)
                print_error "Invalid choice. Please run the script again and select 1-4."
                exit 1
                ;;
        esac
    else
        # Non-interactive mode (piped from curl) - run one-click installation
        one_click_installation
    fi
}

# Run main function
main "$@"
