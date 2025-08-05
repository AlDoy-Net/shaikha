# Shaikha Aldoy Presentation Slides

A beautiful, interactive presentation viewer built for GitHub Pages.

## Features

✨ **Unified Entry Point**: Single HTML file with all functionality
🎯 **Slideshow Mode**: Navigate slides with keyboard/button controls
🖼️ **Gallery View**: Grid layout with modal full-size viewing
📱 **Responsive Design**: Works perfectly on all devices
⛶ **Fullscreen Support**: Press `F` or click button for fullscreen
⌨️ **Keyboard Navigation**: Arrow keys, spacebar, ESC support
🚀 **GitHub Pages Ready**: Static HTML deployment

## Quick Start

1. **View Online**: Simply open `index.html` in any web browser
2. **Local Development**: Use the PHP script to regenerate when adding new slides

## Building

To regenerate the static HTML after adding new slides:

```bash
./build.sh
```

Or manually:

```bash
php index.php > index.html
```

## Adding New Slides

1. Add PNG files to the `slides/` directory
2. Name them numerically (e.g., `43.png`, `44.png`)
3. Run the build script to regenerate `index.html`

## Keyboard Controls

- **Home Screen**:
  - `F`: Toggle fullscreen
- **Slideshow Mode**:
  - `←/→`: Navigate slides
  - `Space`: Next slide
  - `F`: Toggle fullscreen
  - `ESC`: Exit fullscreen
- **Gallery Mode**:
  - `F`: Toggle fullscreen
  - `ESC`: Exit fullscreen or close modal
  - `Click`: View slide in modal

## File Structure

```text
├── index.html          # Generated static HTML (GitHub Pages entry point)
├── index.php           # PHP generator script
├── build.sh            # Build script
├── slides/             # Slide images directory
│   ├── 1.png
│   ├── 2.png
│   └── ...
└── README.md           # This file
```

## GitHub Pages Deployment

1. Push the repository to GitHub
2. Enable GitHub Pages in repository settings
3. Set source to root directory
4. Your presentation will be available at `https://[username].github.io/[repository-name]/`

## Browser Support

- ✅ Chrome, Firefox, Safari, Edge (modern versions)
- ✅ Mobile browsers (iOS Safari, Chrome Mobile)
- ✅ Fullscreen API support in all modern browsers

---

**Total Slides**: 36 • **Format**: PNG • **Generated**: August 2025
