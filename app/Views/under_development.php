<!doctype html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Feature Under Development</title>

  <script src="/_sdk/element_sdk.js"></script>
  <script src="/_sdk/data_sdk.js" type="text/javascript"></script>
  <script src="https://cdn.tailwindcss.com" type="text/javascript"></script>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

  <style>
    body {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
      font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
      height: 100%;
      overflow-x: hidden;
    }

    html {
      height: 100%;
    }

    .hero-section {
      min-height: 100%;
      background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
      position: relative;
      overflow: hidden;
    }

    .background-pattern {
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      opacity: 0.1;
      pointer-events: none;
    }

    .content-wrapper {
      position: relative;
      z-index: 2;
      padding: 80px 0;
    }

    .icon-container {
      animation: pulse 2s ease-in-out infinite;
    }

    @keyframes pulse {
      0%, 100% { transform: scale(1); opacity: 1; }
      50% { transform: scale(1.05); opacity: 0.8; }
    }

    .hero-icon { width: 120px; height: 120px; margin: 0 auto 30px; }
    .hero-title { font-size: 3.5rem; font-weight: 700; color: #fff; margin-bottom: 20px; line-height: 1.2; }
    .hero-subtitle { font-size: 1.75rem; font-weight: 500; color: #f0f0f0; margin-bottom: 15px; }
    .hero-description { font-size: 1.25rem; color: #e0e0e0; margin-bottom: 40px; line-height: 1.6; }

    .features-section { margin-top: 60px; }
    .feature-item { text-align: center; color: #fff; margin-bottom: 30px; }
    .feature-icon { font-size: 3rem; margin-bottom: 15px; display: block; }
    .feature-text { font-size: 1rem; font-weight: 500; }

    .footer-text { margin-top: 60px; color: rgba(255,255,255,0.8); font-size: 0.9rem; text-align: center; }

    .back-link {
      position: absolute;
      top: 30px;
      left: 30px;
      color: rgba(255,255,255,0.9);
      text-decoration: none;
      font-weight: 500;
      transition: all 0.3s ease;
      z-index: 3;
    }

    .back-link:hover {
      color: #fff;
      transform: translateX(-5px);
    }

    @media (max-width: 768px) {
      .hero-title { font-size: 2.5rem; }
      .hero-subtitle { font-size: 1.5rem; }
      .hero-description { font-size: 1.1rem; }
      .hero-icon { width: 90px; height: 90px; }
      .content-wrapper { padding: 40px 0; }
      .back-link { top: 20px; left: 20px; }
    }
  </style>
</head>

<body>
  <div class="hero-section d-flex align-items-center">
    <a href="#" class="back-link" onclick="history.back(); return false;">← Back</a>
    <svg class="background-pattern" xmlns="http://www.w3.org/2000/svg">
      <defs>
        <pattern id="grid" width="50" height="50" patternUnits="userSpaceOnUse">
          <circle cx="25" cy="25" r="2" fill="white" />
        </pattern>
      </defs>
      <rect width="100%" height="100%" fill="url(#grid)" />
    </svg>

    <div class="container content-wrapper">
      <div class="row justify-content-center">
        <div class="col-lg-8 col-md-10 text-center">
          <div class="icon-container mb-4">
            <svg class="hero-icon" viewBox="0 0 120 120" fill="none" xmlns="http://www.w3.org/2000/svg">
              <circle cx="60" cy="60" r="55" fill="white" opacity="0.2" />
              <circle cx="60" cy="60" r="45" fill="white" />
              <path d="M40 50h40M40 60h40M40 70h25" stroke="#6366f1" stroke-width="4" stroke-linecap="round" />
              <circle cx="75" cy="70" r="8" fill="#6366f1" />
              <path d="M71 70l3 3 6-6" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
            </svg>
          </div>
          <p class="hero-subtitle" id="subtitle">Under Development</p>
          <p class="hero-description" id="description">
            This feature is currently being developed and will be available soon.
            We're working hard to bring you the best experience possible.
          </p>

          <div class="features-section">
            <div class="row">
              <div class="col-md-4">
                <div class="feature-item">
                  <span class="feature-icon">🚀</span>
                  <div class="feature-text">In Progress</div>
                </div>
              </div>
              <div class="col-md-4">
                <div class="feature-item">
                  <span class="feature-icon">🔧</span>
                  <div class="feature-text">Being Built</div>
                </div>
              </div>
              <div class="col-md-4">
                <div class="feature-item">
                  <span class="feature-icon">⏰</span>
                  <div class="feature-text">Coming Soon</div>
                </div>
              </div>
            </div>
          </div>

          <div class="footer-text" id="footer-text">
            We appreciate your patience while we work on this feature.
          </div>
        </div>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    const defaultConfig = {
      background_color: '#6366f1',
      secondary_color: '#8b5cf6',
      text_color: '#ffffff',
      font_family: 'Inter',
      font_size: 16,
      main_title: 'Feature Name',
      subtitle: 'Under Development',
      description: "This feature is currently being developed and will be available soon. We're working hard to bring you the best experience possible.",
      footer_text: 'We appreciate your patience while we work on this feature.'
    };

    async function onConfigChange(config) {
      const customFont = config.font_family || defaultConfig.font_family;
      const baseFontStack = '-apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif';
      const fontFamily = `${customFont}, ${baseFontStack}`;
      const baseSize = config.font_size || defaultConfig.font_size;

      document.body.style.fontFamily = fontFamily;

      const heroSection = document.querySelector('.hero-section');
      heroSection.style.background = `linear-gradient(135deg, ${config.background_color || defaultConfig.background_color} 0%, ${config.secondary_color || defaultConfig.secondary_color} 100%)`;

      const textColor = config.text_color || defaultConfig.text_color;
      document.querySelectorAll('.hero-title, .hero-subtitle, .hero-description, .footer-text, .feature-item').forEach(el => el.style.color = textColor);
    }

    if (window.elementSdk) {
      window.elementSdk.init({
        defaultConfig,
        onConfigChange,
      });
    }
  </script>
</body>
</html>
