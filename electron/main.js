import { app, BrowserWindow, session } from 'electron';
import path from 'path';
import { fileURLToPath } from 'url';

const __filename = fileURLToPath(import.meta.url);
const __dirname = path.dirname(__filename);

// Configuration
const REMOTE_SERVER = process.env.REMOTE_SERVER || 'https://logopedics.ru'; // Replace with your server URL
const isDev = process.env.NODE_ENV === 'development';

let mainWindow;

function createWindow() {
    mainWindow = new BrowserWindow({
        width: 1200,
        height: 800,
        webPreferences: {
            nodeIntegration: true,
            contextIsolation: false,
            webSecurity: true,
            allowRunningInsecureContent: false
        }
    });

    // Set CSP headers
    session.defaultSession.webRequest.onHeadersReceived((details, callback) => {
        callback({
            responseHeaders: {
                ...details.responseHeaders,
                'Content-Security-Policy': [
                    "default-src 'self' " + REMOTE_SERVER + "; " +
                    "script-src 'self' 'unsafe-inline' 'unsafe-eval'; " +
                    "style-src 'self' 'unsafe-inline'; " +
                    "img-src 'self' data: https:; " +
                    "connect-src 'self' " + REMOTE_SERVER + ";"
                ]
            }
        });
    });

    // Load the remote URL
    if (isDev) {
        // In development, you might want to load from local server
        mainWindow.loadURL('http://localhost');
        mainWindow.webContents.openDevTools();
    } else {
        // In production, load from remote server
        mainWindow.loadURL(REMOTE_SERVER);
    }

    // Handle navigation
    mainWindow.webContents.on('will-navigate', (event, url) => {
        // Only allow navigation within your domain
        if (!url.startsWith(REMOTE_SERVER) && !url.startsWith('http://localhost')) {
            event.preventDefault();
        }
    });
}

app.whenReady().then(() => {
    createWindow();

    app.on('activate', () => {
        if (BrowserWindow.getAllWindows().length === 0) {
            createWindow();
        }
    });
});

app.on('window-all-closed', () => {
    if (process.platform !== 'darwin') {
        app.quit();
    }
});