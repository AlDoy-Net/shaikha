<?php
// PHP script to generate static HTML for GitHub Pages
// This script scans the slides directory and generates a complete static HTML file

// Get list of slide files
$slidesDir = 'slides/';
$slideFiles = [];
if (is_dir($slidesDir)) {
    $files = scandir($slidesDir);
    foreach ($files as $file) {
        if (preg_match('/(\d+)\.jpeg$/', $file, $matches)) {
            $slideFiles[] = intval($matches[1]);
        }
    }
    sort($slideFiles);
}
$totalSlides = count($slideFiles);

// Output the complete static HTML
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shaikha Aldoy Presentations</title>
    <style>
        * {
            box-sizing: border-box;
        }
        
        body {
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
        }

        /* Home Page Styles */
        .home-mode {
            display: none;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
        }
        
        .home-mode.active {
            display: flex;
        }
        
        .container {
            text-align: center;
            background: white;
            padding: 60px 40px;
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
            max-width: 500px;
        }
        
        h1 {
            color: #333;
            margin-bottom: 20px;
            font-size: 2.5em;
        }
        
        .subtitle {
            color: #666;
            margin-bottom: 40px;
            font-size: 1.1em;
        }
        
        .options {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }
        
        .option {
            padding: 20px;
            background: #f8f9fa;
            border: 2px solid #e9ecef;
            border-radius: 10px;
            text-decoration: none;
            color: #333;
            transition: all 0.3s ease;
            cursor: pointer;
        }
        
        .option:hover {
            background: #667eea;
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(102, 126, 234, 0.3);
        }
        
        .option h3 {
            margin: 0 0 10px 0;
            font-size: 1.3em;
        }
        
        .option p {
            margin: 0;
            font-size: 0.9em;
            opacity: 0.8;
        }
        
        .stats {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #eee;
            color: #666;
            font-size: 0.9em;
        }

        /* Slideshow Styles */
        .slideshow-mode {
            background: #000;
            display: none;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }
        
        .slideshow-mode.active {
            display: flex;
        }
        
        .slideshow-container {
            position: relative;
            max-width: 95vw;
            max-height: 95vh;
            margin: auto;
        }
        
        .slide {
            display: none;
            width: 100%;
            height: auto;
            max-height: 95vh;
            object-fit: contain;
        }
        
        .slide.active {
            display: block;
        }
        
        .controls {
            position: fixed;
            bottom: 20px;
            left: 50%;
            transform: translateX(-50%);
            background: rgba(0,0,0,0.8);
            padding: 15px 25px;
            border-radius: 30px;
            display: flex;
            gap: 20px;
            align-items: center;
            z-index: 100;
        }
        
        .control-btn {
            background: #fff;
            border: none;
            padding: 12px 20px;
            border-radius: 8px;
            cursor: pointer;
            font-size: 14px;
            font-weight: 500;
            transition: all 0.2s ease;
        }
        
        .control-btn:hover {
            background: #f0f0f0;
            transform: translateY(-1px);
        }
        
        .control-btn:disabled {
            background: #666;
            color: #999;
            cursor: not-allowed;
            transform: none;
        }
        
        .slide-counter {
            color: white;
            font-size: 16px;
            font-weight: 500;
        }

        /* Gallery Styles */
        .gallery-mode {
            background: #f5f5f5;
            padding: 20px;
            min-height: 100vh;
            display: none;
        }
        
        .gallery-mode.active {
            display: block;
        }
        
        .gallery-header {
            text-align: center;
            margin-bottom: 30px;
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .gallery {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 20px;
            max-width: 1400px;
            margin: 0 auto;
        }
        
        .slide-card {
            background: white;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            overflow: hidden;
            transition: all 0.3s ease;
            cursor: pointer;
        }
        
        .slide-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.2);
        }
        
        .slide-card img {
            width: 100%;
            height: 200px;
            object-fit: cover;
            border-bottom: 1px solid #eee;
        }
        
        .slide-info {
            padding: 15px;
            text-align: center;
        }
        
        .slide-number {
            font-size: 18px;
            font-weight: bold;
            color: #333;
        }

        /* Modal for full-size view */
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.95);
        }
        
        .modal-content {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            max-width: 95%;
            max-height: 95%;
        }
        
        .modal img {
            width: 100%;
            height: auto;
            max-height: 95vh;
            object-fit: contain;
        }
        
        .close {
            position: absolute;
            top: 15px;
            right: 35px;
            color: #f1f1f1;
            font-size: 40px;
            font-weight: bold;
            cursor: pointer;
            z-index: 1001;
        }
        
        .close:hover {
            color: #bbb;
        }

        /* Navigation */
        .nav-bar {
            position: fixed;
            top: 20px;
            left: 20px;
            background: rgba(0,0,0,0.8);
            padding: 10px 20px;
            border-radius: 25px;
            z-index: 200;
        }
        
        .nav-btn {
            background: #fff;
            border: none;
            padding: 8px 15px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
            margin-right: 10px;
            transition: all 0.2s ease;
        }
        
        .nav-btn:hover {
            background: #f0f0f0;
        }
        
        .nav-btn:last-child {
            margin-right: 0;
        }

        /* Fullscreen Button */
        .fullscreen-btn {
            position: fixed;
            top: 20px;
            right: 20px;
            background: rgba(0,0,0,0.8);
            color: white;
            border: none;
            padding: 12px 16px;
            border-radius: 8px;
            cursor: pointer;
            font-size: 16px;
            z-index: 200;
            transition: all 0.2s ease;
        }
        
        .fullscreen-btn:hover {
            background: rgba(0,0,0,0.9);
            transform: scale(1.05);
        }

        /* Keyboard hints */
        .keyboard-hints {
            position: fixed;
            top: 70px;
            right: 20px;
            background: rgba(0,0,0,0.8);
            color: white;
            padding: 10px 15px;
            border-radius: 8px;
            font-size: 12px;
            z-index: 200;
            opacity: 0.8;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .container {
                margin: 20px;
                padding: 40px 20px;
            }
            
            .gallery {
                grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
                gap: 15px;
            }
            
            .controls {
                padding: 10px 15px;
                gap: 10px;
            }
            
            .control-btn {
                padding: 8px 12px;
                font-size: 12px;
            }
        }
    </style>
</head>
<body>
    
    <!-- HOME PAGE -->
    <div class="home-mode active" id="home-mode">
        <div class="container">
            <h1>📊 Presentation Slides</h1>
            <div class="subtitle">Choose your viewing experience</div>
            
            <div class="options">
                <div class="option" onclick="changeMode('slideshow')">
                    <h3>🎯 Slideshow Mode</h3>
                    <p>Navigate through slides sequentially with keyboard controls</p>
                </div>
                
                <div class="option" onclick="changeMode('gallery')">
                    <h3>🖼️ Gallery View</h3>
                    <p>Browse all slides in a grid layout for quick overview</p>
                </div>
                
                <div class="option" onclick="window.open('slides/', '_blank')">
                    <h3>📁 File Browser</h3>
                    <p>Direct access to individual slide files</p>
                </div>
            </div>
            
            <div class="stats">
                📊 Total slides: <?php echo $totalSlides; ?> • Format: jpeg
            </div>
        </div>
    </div>

    <!-- SLIDESHOW MODE -->
    <div class="slideshow-mode" id="slideshow-mode">
        <div class="nav-bar">
            <button class="nav-btn" onclick="changeMode('home')">🏠 Home</button>
            <button class="nav-btn" onclick="changeMode('gallery')">🖼️ Gallery</button>
        </div>
        
        <button class="fullscreen-btn" onclick="toggleFullscreen()" title="Toggle Fullscreen (F)">⛶</button>
        
        <div class="keyboard-hints">
            <div>← → Arrow keys: Navigate</div>
            <div>F: Toggle fullscreen</div>
            <div>ESC: Exit fullscreen</div>
        </div>
        
        <div class="slideshow-container" id="slideshow-container">
            <?php foreach ($slideFiles as $index => $slideNum): ?>
                <img src="slides/<?php echo $slideNum; ?>.jpeg" 
                     class="slide <?php echo $index === 0 ? 'active' : ''; ?>" 
                     alt="Slide <?php echo $slideNum; ?>"
                     loading="<?php echo $index < 3 ? 'eager' : 'lazy'; ?>">
            <?php endforeach; ?>
        </div>
        
        <div class="controls">
            <button class="control-btn" id="prevBtn">← Previous</button>
            <span class="slide-counter">
                <span id="currentSlide">1</span> / <span id="totalSlides"><?php echo $totalSlides; ?></span>
            </span>
            <button class="control-btn" id="nextBtn">Next →</button>
        </div>
    </div>

    <!-- GALLERY MODE -->
    <div class="gallery-mode" id="gallery-mode">
        <div class="nav-bar">
            <button class="nav-btn" onclick="changeMode('home')">🏠 Home</button>
            <button class="nav-btn" onclick="changeMode('slideshow')">🎯 Slideshow</button>
        </div>
        
        <button class="fullscreen-btn" onclick="toggleFullscreen()" title="Toggle Fullscreen (F)">⛶</button>
        
        <div class="keyboard-hints">
            <div>F: Toggle fullscreen</div>
            <div>ESC: Exit fullscreen/Close modal</div>
            <div>Click slide: View full size</div>
        </div>
        
        <div class="gallery-header">
            <h1>Shaikha Aldoy Presentation Slides</h1>
            <p>Click on any slide to view full size • Total slides: <?php echo $totalSlides; ?></p>
        </div>
        
        <div class="gallery" id="gallery">
            <?php foreach ($slideFiles as $slideNum): ?>
                <div class="slide-card" onclick="openModal('slides/<?php echo $slideNum; ?>.jpeg')">
                    <img src="slides/<?php echo $slideNum; ?>.jpeg" alt="Slide <?php echo $slideNum; ?>" loading="lazy">
                    <div class="slide-info">
                        <div class="slide-number">Slide <?php echo $slideNum; ?></div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        
        <!-- Modal -->
        <div id="modal" class="modal">
            <span class="close" onclick="closeModal()">&times;</span>
            <div class="modal-content">
                <img id="modal-img" src="" alt="">
            </div>
        </div>
    </div>

    <script>
        // Global variables
        let currentSlideIndex = 0;
        const totalSlides = <?php echo $totalSlides; ?>;
        const slideNumbers = [<?php echo implode(',', $slideFiles); ?>];
        
        // Mode switching
        function changeMode(mode) {
            // Hide all modes
            document.querySelectorAll('.home-mode, .slideshow-mode, .gallery-mode').forEach(el => {
                el.classList.remove('active');
            });
            
            // Show selected mode
            const targetMode = document.getElementById(mode + '-mode');
            if (targetMode) {
                targetMode.classList.add('active');
            }
            
            // Initialize mode-specific functionality
            if (mode === 'slideshow') {
                initializeSlideshow();
            } else if (mode === 'gallery') {
                initializeGallery();
            }
        }
        
        // Fullscreen functionality
        function toggleFullscreen() {
            if (!document.fullscreenElement) {
                document.documentElement.requestFullscreen().catch(err => {
                    console.log('Error attempting to enable fullscreen:', err.message);
                });
            } else {
                if (document.exitFullscreen) {
                    document.exitFullscreen();
                }
            }
        }
        
        // Global keyboard handlers
        document.addEventListener('keydown', function(e) {
            // F key for fullscreen
            if (e.key === 'f' || e.key === 'F') {
                e.preventDefault();
                toggleFullscreen();
            }
            
            // ESC key
            if (e.key === 'Escape') {
                if (document.fullscreenElement) {
                    document.exitFullscreen();
                }
                // Close modal if in gallery mode
                const modal = document.getElementById('modal');
                if (modal && modal.style.display === 'block') {
                    closeModal();
                }
            }
        });

        // Slideshow functionality
        function initializeSlideshow() {
            const slides = document.querySelectorAll('.slide');
            
            window.showSlide = function(n) {
                if (slides.length === 0) return;
                
                slides[currentSlideIndex].classList.remove('active');
                currentSlideIndex = Math.max(0, Math.min(n, slides.length - 1));
                slides[currentSlideIndex].classList.add('active');
                document.getElementById('currentSlide').textContent = currentSlideIndex + 1;
                
                // Update button states
                document.getElementById('prevBtn').disabled = currentSlideIndex === 0;
                document.getElementById('nextBtn').disabled = currentSlideIndex === slides.length - 1;
            };
            
            window.nextSlide = function() {
                if (currentSlideIndex < slides.length - 1) {
                    showSlide(currentSlideIndex + 1);
                }
            };
            
            window.prevSlide = function() {
                if (currentSlideIndex > 0) {
                    showSlide(currentSlideIndex - 1);
                }
            };
            
            // Event listeners
            document.getElementById('nextBtn').onclick = nextSlide;
            document.getElementById('prevBtn').onclick = prevSlide;
            
            // Keyboard navigation for slideshow
            document.addEventListener('keydown', function(e) {
                if (document.getElementById('slideshow-mode').classList.contains('active')) {
                    if (e.key === 'ArrowRight' || e.key === ' ') {
                        e.preventDefault();
                        nextSlide();
                    } else if (e.key === 'ArrowLeft') {
                        e.preventDefault();
                        prevSlide();
                    }
                }
            });
            
            // Initialize
            showSlide(0);
        }
        
        // Gallery functionality
        function initializeGallery() {
            const modal = document.getElementById('modal');
            const modalImg = document.getElementById('modal-img');
            
            window.openModal = function(imageSrc) {
                modal.style.display = 'block';
                modalImg.src = imageSrc;
            };
            
            window.closeModal = function() {
                modal.style.display = 'none';
            };
            
            // Modal click outside to close
            modal.onclick = function(e) {
                if (e.target === modal) {
                    closeModal();
                }
            };
        }
        
        // Initialize on page load
        document.addEventListener('DOMContentLoaded', function() {
            // Home mode is active by default
            initializeSlideshow();
            initializeGallery();
        });
    </script>
</body>
</html>
