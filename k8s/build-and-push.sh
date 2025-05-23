#!/bin/bash

# Configuration
REGISTRY="docker.io"  # Docker Hub registry
IMAGE_NAME="armagnac1/laravel-app"
VERSION=$(git rev-parse --short HEAD)  # Use git commit hash as version
LATEST_TAG="latest"

# Build the image
echo "Building Docker image..."
docker build -t ${REGISTRY}/${IMAGE_NAME}:${VERSION} -t ${REGISTRY}/${IMAGE_NAME}:${LATEST_TAG} .

# Login to Docker Hub
echo "Logging in to Docker Hub..."
docker login

# Push the image
echo "Pushing Docker image..."
docker push ${REGISTRY}/${IMAGE_NAME}:${VERSION}
docker push ${REGISTRY}/${IMAGE_NAME}:${LATEST_TAG}

echo "Done! Image pushed to ${REGISTRY}/${IMAGE_NAME}:${VERSION}"
echo "Update your k8s/deployment.yaml with the new image version: ${VERSION}"
