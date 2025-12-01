<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tuklas Nasugbu - Discover Paradise in Batangas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= base_url('assets/css/landingPage.css')?>">
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg fixed-top" id="mainNav">
        <div class="container">
            <a class="navbar-brand" href="#home">
                <img src="<?= base_url('assets/img/Tuklas_logo.png')?>" alt="Tuklas Nasugbu Logo">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav mx-auto">
                    <li class="nav-item"><a class="nav-link" href="#home">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="#about">About</a></li>
                    <li class="nav-item"><a class="nav-link" href="#attractions">Attractions</a></li>
                    <li class="nav-item"><a class="nav-link" href="#activities">Activities</a></li>
                    <li class="nav-item"><a class="nav-link" href="#gallery">Gallery</a></li>
                    <li class="nav-item"><a class="nav-link" href="#contact">Contact</a></li>
                </ul>
                <div class="d-flex">
                    <button class="btn btn-login" data-bs-toggle="modal" data-bs-target="#authModal" onclick="setAuthTab('login')">Login</button>
                    <button class="btn btn-signup" data-bs-toggle="modal" data-bs-target="#authModal" onclick="setAuthTab('signup')">Sign Up</button>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero-section" id="home">
        <div class="hero-slideshow">
            <div class="hero-slide active" style="background-image: url('https://mediaim.expedia.com/destination/2/c111173cb24a151078a79ff131bce7b9.jpg');"></div>
            <div class="hero-slide" style="background-image: url('https://staycations.ph/wp-content/uploads/2023/07/Club-Punta-Fuego24-1024x683.jpg');"></div>
            <div class="hero-slide" style="background-image: url('https://cf.bstatic.com/xdata/images/hotel/max1024x768/208785010.jpg?k=0b449da4af81bad51bb28c47646ac6a99a6c982af065423b5d57133700a73187&o=&hp=1');"></div>
            <div class="hero-slide" style="background-image: url('https://wanderera.com/wp-content/uploads/2022/07/Pico-91-2048x1529.jpg');"></div>
            <div class="hero-slide" style="background-image: url('https://images.unsplash.com/photo-1706066954162-d557cc64a163?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&ixid=M3w3Nzg4Nzd8MHwxfHNlYXJjaHwxfHx0cm9waWNhbCUyMGlzbGFuZCUyMHBhcmFkaXNlJTIwYWVyaWFsfGVufDF8fHx8MTc2MjE1NzczNHww&ixlib=rb-4.1.0&q=80&w=1080');"></div>
        </div>
        <div class="hero-overlay"></div>
        <div class="hero-content">
            <div class="hero-badge">
                <i class="bi bi-water"></i>
                <span>DISCOVER PARADISE</span>
            </div>
            <h1 class="hero-title">Tuklas Nasugbu</h1>
            <p class="hero-subtitle">Where pristine beaches meet endless adventure in Batangas</p>
            <div class="hero-buttons">
                <a href="#attractions" class="btn btn-hero-primary">Explore Attractions <span>→</span></a>
                <a href="#contact" class="btn btn-hero-secondary">Plan Your Visit</a>
            </div>
        </div>
        <a class="scroll-indicator" href="#about">
            <span>SCROLL DOWN</span>
            <i class="bi bi-chevron-down"></i>
        </a>
    </section>

    <!-- About Section -->
     <section id="about">
        <div class="container">
            <div class="text-center mb-5">
                <div class="section-badge">About Nasugbu</div>
                <h1 class="section-title">Your Gateway to Paradise</h1>
                <p class="section-description">
                    Nasugbu is a coastal municipality in the province of Batangas, Philippines, renowned for its stunning beaches, 
                    crystal-clear waters, and vibrant marine life. Located along the West Philippine Sea, it offers the perfect blend 
                    of natural beauty, adventure, and relaxation.
                </p>
            </div>
            <div class="row g-4 mb-5">
                <div class="col-md-6 col-lg-3"><div class="stat-card"><div class="stat-icon"><i class="bi bi-geo-alt-fill"></i></div><div class="stat-value">70km</div><div class="stat-label">From Manila</div></div></div>
                <div class="col-md-6 col-lg-3"><div class="stat-card"><div class="stat-icon"><i class="bi bi-water"></i></div><div class="stat-value">15+</div><div class="stat-label">Total Spots</div></div></div>
                <div class="col-md-6 col-lg-3"><div class="stat-card"><div class="stat-icon"><i class="bi bi-people-fill"></i></div><div class="stat-value">150k+</div><div class="stat-label">Annual Visitors</div></div></div>
                <div class="col-md-6 col-lg-3"><div class="stat-card"><div class="stat-icon"><i class="bi bi-award-fill"></i></div><div class="stat-value">Top 10</div><div class="stat-label">Beach Destinations</div></div></div>
            </div>
            <div class="row g-4">
                <div class="col-md-6"><div class="info-card primary"><div class="info-icon-box primary"><i class="bi bi-water"></i></div><h3>Natural Paradise</h3><p>From the famous Fortune Island with its Greek-inspired ruins to the serene Munting Buhangin Beach, Nasugbu offers diverse coastal experiences. The area is blessed with white sand beaches, coral reefs, and scenic mountain views.</p></div></div>
                <div class="col-md-6"><div class="info-card secondary"><div class="info-icon-box secondary"><i class="bi bi-award-fill"></i></div><h3>Adventure Awaits</h3><p>Whether you're into diving, snorkeling, hiking, or simply lounging by the beach, Nasugbu has something for everyone. The municipality is also home to world-class resorts and local accommodations that cater to all types of travelers.</p></div></div>
            </div>
        </div>
    </section>

    <!-- Attractions Section -->
    <section id="attractions">
        <div class="container">
            <div class="text-center mb-5">
                <div class="section-badge">Top Attractions</div>
                <h1 class="section-title">Must-Visit Destinations</h1>
                <p class="section-description">Discover the must-visit destinations that make Nasugbu a premier tourist spot.</p>
            </div>
            <div class="row g-4" id="attractionsGrid">
              <div class="col-12 text-center p-4 text-muted">Loading attractions...</div>
            </div>

        </div>
    </section>

    <!-- Activities Section -->
     <section id="activities">
        <div class="container">
            <div class="text-center mb-5">
                <div class="section-badge">Things to Do</div>
                <h1 class="section-title">Explore Activities</h1>
                <p class="section-description">From relaxing beach activities to thrilling adventures, Nasugbu offers endless possibilities.</p>
            </div>
            <div class="row g-4">
                <div class="col-md-6 col-lg-3"><div class="activity-card"><div class="activity-icon"><i class="bi bi-water"></i></div><h3 class="activity-title">Water Activities</h3><ul class="activity-list"><li>Snorkeling & Diving</li><li>Island Hopping</li><li>Jet Skiing</li><li>Kayaking</li><li>Beach Volleyball</li><li>Swimming</li></ul></div></div>
                <div class="col-md-6 col-lg-3"><div class="activity-card"><div class="activity-icon"><i class="bi bi-image-alt"></i></div><h3 class="activity-title">Land Adventures</h3><ul class="activity-list"><li>Mountain Hiking</li><li>Trail Running</li><li>Nature Photography</li><li>Bird Watching</li><li>ATV Riding</li><li>Camping</li></ul></div></div>
                <div class="col-md-6 col-lg-3"><div class="activity-card"><div class="activity-icon"><i class="bi bi-camera"></i></div><h3 class="activity-title">Sightseeing</h3><ul class="activity-list"><li>Fortune Island Tour</li><li>Sunset Viewing</li><li>Lighthouse Visits</li><li>Beach Exploration</li><li>Local Market Tours</li><li>Cultural Sites</li></ul></div></div>
                <div class="col-md-6 col-lg-3"><div class="activity-card"><div class="activity-icon"><i class="bi bi-cup-hot"></i></div><h3 class="activity-title">Food & Culture</h3><ul class="activity-list"><li>Fresh Seafood Dining</li><li>Local Cuisine Tasting</li><li>Beach BBQ</li><li>Coconut Products</li><li>Farm-to-Table Experience</li><li>Traditional Festivals</li></ul></div></div>
            </div>
        </div>
    </section>

    <!-- Gallery Section -->
    <section id="gallery">
        <div class="container">
            <div class="text-center mb-5">
                <div class="section-badge">Gallery</div>
                <h1 class="section-title">Discover Nasugbu's Beauty</h1>
                <p class="section-description">Experience the beauty of Nasugbu through stunning imagery.</p>
            </div>
            <div class="gallery-grid">
                <div class="gallery-item"><img src="https://images.unsplash.com/photo-1710104434497-d7417d2d7d2b?w=800" alt="Pristine Beach"></div>
                <div class="gallery-item"><img src="https://images.unsplash.com/photo-1646064722199-947b34d0529b?w=400" alt="Beach Sunset"></div>
                <div class="gallery-item"><img src="https://images.unsplash.com/photo-1744656317897-c97c8f3b1371?w=400" alt="Coral Reef"></div>
                <div class="gallery-item"><img src="https://images.unsplash.com/photo-1635159804596-06b79defff8e?w=400" alt="Mountain Trail"></div>
                <div class="gallery-item"><img src="https://images.unsplash.com/photo-1617945174127-e47d409e47c1?w=400" alt="Water Sports"></div>
                <div class="gallery-item"><img src="https://images.unsplash.com/photo-1729718110342-83f7c6b0e333?w=800" alt="Beach Resort"></div>
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section id="contact">
        <div class="container">
            <div class="text-center mb-5">
                <div class="section-badge">Plan Your Visit</div>
                <h1 class="section-title">Get in Touch</h1>
                <p class="section-description">Everything you need to know for your Nasugbu adventure.</p>
            </div>
            <div class="row g-4 mb-5">
                <div class="col-md-6 col-lg-3"><div class="contact-card"><div class="contact-icon"><i class="bi bi-geo-alt-fill"></i></div><h5>Location</h5><p>Nasugbu, Batangas</p><p>Philippines, 4231</p></div></div>
                <div class="col-md-6 col-lg-3"><div class="contact-card"><div class="contact-icon"><i class="bi bi-telephone-fill"></i></div><h5>Contact</h5><p>Tourism: (043) 416-0123</p><p>Emergency: 911</p></div></div>
                <div class="col-md-6 col-lg-3"><div class="contact-card"><div class="contact-icon"><i class="bi bi-envelope-fill"></i></div><h5>Email</h5><p>tourism@nasugbu.gov.ph</p><p>info@visitnasugbu.ph</p></div></div>
                <div class="col-md-6 col-lg-3"><div class="contact-card"><div class="contact-icon"><i class="bi bi-clock-fill"></i></div><h5>Best Time to Visit</h5><p>November to May</p><p>Dry season recommended</p></div></div>
            </div>
            <div class="mb-4">
                <h2 class="text-center mb-4">How to Get There</h2>
                <div class="row g-4">
                    <div class="col-md-6"><div class="travel-card"><div class="travel-icon"><i class="bi bi-bus-front"></i></div><h4>By Bus</h4><p>Take a bus from Manila to Nasugbu (3-4 hours). Buses depart from Pasay, Cubao, or Alabang terminals. Regular trips available throughout the day.</p></div></div>
                    <div class="col-md-6"><div class="travel-card"><div class="travel-icon"><i class="bi bi-airplane-fill"></i></div><h4>By Air + Land</h4><p>Fly to Ninoy Aquino International Airport (Manila), then take a bus or private car to Nasugbu. Travel time approximately 2-3 hours from airport.</p></div></div>
                </div>
            </div>
            <div class="cta-section">
                <h3>Ready to Explore Nasugbu?</h3>
                <p>Start planning your trip today and discover why Nasugbu is one of the Philippines' most beloved beach destinations. From pristine shores to mountain adventures, your perfect getaway awaits.</p>
                <a href="https://www.nasugbu.gov.ph" target="_blank" rel="noopener noreferrer" class="btn-cta">Visit Official Website</a>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer>
        <div class="container">
            <div class="row">
                <div class="col-md-4 mb-4">
                    <h5>Tuklas Nasugbu</h5>
                    <p class="opacity-75">Discover the beauty and adventure of Nasugbu, Batangas - your perfect tropical getaway destination.</p>
                </div>
                <div class="col-md-4 mb-4">
                    <h5>Quick Links</h5>
                    <ul class="list-unstyled">
                        <li><a href="#about">About Nasugbu</a></li>
                        <li><a href="#attractions">Attractions</a></li>
                        <li><a href="#activities">Activities</a></li>
                        <li><a href="#gallery">Gallery</a></li>
                        <li><a href="#contact">Contact</a></li>
                    </ul>
                </div>
                <div class="col-md-4 mb-4">
                    <h5>Connect With Us</h5>
                    <div class="d-flex gap-2">
                        <a href="#" class="btn btn-outline-light btn-sm" aria-label="Facebook"><i class="bi bi-facebook"></i></a>
                        <a href="#" class="btn btn-outline-light btn-sm" aria-label="Instagram"><i class="bi bi-instagram"></i></a>
                        <a href="mailto:tourism@nasugbu.gov.ph" class="btn btn-outline-light btn-sm" aria-label="Email"><i class="bi bi-envelope"></i></a>
                        <a href="tel:0434160123" class="btn btn-outline-light btn-sm" aria-label="Telephone"><i class="bi bi-telephone"></i></a>
                    </div>
                </div>
            </div>
            <hr class="opacity-25 my-4">
            <div class="text-center opacity-75">
                <p>&copy; 2025 Tuklas Nasugbu. All rights reserved. | A promotional website for tourism in Nasugbu, Batangas</p>
            </div>
        </div>
    </footer>

    <!-- Auth Modal -->
    <div class="modal fade" id="authModal" tabindex="-1">
      <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
          <div class="modal-body auth-modal-body p-0">
            <div class="auth-form-panel">
              <ul class="nav nav-tabs mb-4" role="tablist">
                <li class="nav-item flex-fill"><button id="login-tab" class="nav-link active w-100" data-bs-toggle="tab" data-bs-target="#login" type="button">Login</button></li>
                <li class="nav-item flex-fill"><button id="signup-tab" class="nav-link w-100" data-bs-toggle="tab" data-bs-target="#signup" type="button">Sign Up</button></li>
              </ul>
              <div class="tab-content">
                <div class="tab-pane fade show active" id="login" role="tabpanel">
                  <h3 class="mb-4" style="color: var(--deep-ocean);">Welcome Back</h3>
                  <form id="loginForm" action="<?= base_url('/users/login') ?>" method="post">
                    <div class="mb-3">
                      <label class="form-label">Email Address</label>
                      <div class="position-relative"><i class="bi bi-envelope input-icon"></i><input type="email" name="InputEmail" class="form-control" style="padding-left: 2.5rem;" placeholder="Enter your email" required></div>
                    </div>
                    <div class="mb-3">
                      <label class="form-label">Password</label>
                      <div class="position-relative"><i class="bi bi-lock input-icon"></i><input type="password" name="InputPassword" class="form-control" style="padding-left: 2.5rem;" placeholder="Enter your password" required></div>
                    </div>
                    <div class="mb-4"><a href="#" class="text-decoration-none" style="color: var(--ocean-light);">Forgot password?</a></div>
                    <button type="submit" class="btn btn-primary w-100">Sign In</button>
                  </form>
                </div>
                <div class="tab-pane fade" id="signup" role="tabpanel">
                  <h3 class="mb-4" style="color: var(--deep-ocean);">Create Account</h3>
                  <form id="signupForm" onsubmit="handleSignup(event)">
                    <!-- Common Fields First -->
                    <div class="form-row mb-3">
                      <div>
                        <label class="form-label">First Name</label>
                        <div class="position-relative">
                          <i class="bi bi-person input-icon"></i>
                          <input type="text" name="firstName" class="form-control" style="padding-left: 2.5rem;" placeholder="First name" required>
                        </div>
                      </div>
                      <div>
                        <label class="form-label">Middle Name</label>
                        <div class="position-relative">
                          <i class="bi bi-person input-icon"></i>
                          <input type="text" name="middleName" class="form-control" style="padding-left: 2.5rem;" placeholder="Middle name">
                        </div>
                      </div>
                    </div>
                    <div class="mb-3">
                      <label class="form-label">Last Name</label>
                      <div class="position-relative">
                        <i class="bi bi-person input-icon"></i>
                        <input type="text" name="lastName" class="form-control" style="padding-left: 2.5rem;" placeholder="Last name" required>
                      </div>
                    </div>
                    <div class="mb-3">
                      <label class="form-label">Email Address</label>
                      <div class="position-relative">
                        <i class="bi bi-envelope input-icon"></i>
                        <input type="email" name="email" class="form-control" style="padding-left: 2.5rem;" placeholder="Enter your email" required>
                      </div>
                    </div>
                    
                    <!-- Role Selection Moved Here -->
                    <div class="mb-3">
                      <label class="form-label">Role</label>
                      <div class="position-relative">
                        <i class="bi bi-person-badge input-icon"></i>
                        <select id="signupRole" name="role" class="form-select" style="padding-left: 2.5rem;" required onchange="onSignupRoleChange()">
                          <option value="">Select your role</option>
                          <option value="tourist">Tourist</option>
                          <option value="Spot Owner">Spot Owner</option>
                        </select>
                      </div>
                    </div>

                    <!-- Rest of the fields remain the same -->
                    <!-- Tourist Fields -->
                    <div id="touristFields" style="display:none;">
                      <div class="mb-3">
                        <label class="form-label">Contact Number</label>
                        <div class="position-relative">
                          <i class="bi bi-telephone input-icon"></i>
                          <input type="tel" name="touristContact" class="form-control" style="padding-left: 2.5rem;" placeholder="Contact number">
                        </div>
                      </div>
                      <div class="mb-3">
                        <label class="form-label">Address</label>
                        <div class="position-relative">
                          <i class="bi bi-geo-alt input-icon"></i>
                          <input type="text" name="touristAddress" class="form-control" style="padding-left: 2.5rem;" placeholder="Complete address">
                        </div>
                      </div>
                      <div class="mb-3">
                        <label class="form-label">Emergency Contact Name</label>
                        <div class="position-relative">
                          <i class="bi bi-person-hearts input-icon"></i>
                          <input type="text" name="emergencyContact" class="form-control" style="padding-left: 2.5rem;" placeholder="Emergency contact person">
                        </div>
                      </div>
                      <div class="mb-3">
                        <label class="form-label">Emergency Contact Number</label>
                        <div class="position-relative">
                          <i class="bi bi-telephone-fill input-icon"></i>
                          <input type="tel" name="emergencyNumber" class="form-control" style="padding-left: 2.5rem;" placeholder="Emergency contact number">
                        </div>
                      </div>
                    </div>

                    <!-- Spot Owner Fields -->
                    <div id="ownerFields" style="display:none;">
                      <div class="mb-3">
                        <label class="form-label">Business Name</label>
                        <div class="position-relative">
                          <i class="bi bi-building input-icon"></i>
                          <input type="text" name="businessName" class="form-control" style="padding-left: 2.5rem;" placeholder="Business name">
                        </div>
                      </div>
                      <div class="mb-3">
                        <label class="form-label">Business Contact Number</label>
                        <div class="position-relative">
                          <i class="bi bi-telephone input-icon"></i>
                          <input type="tel" name="businessContact" class="form-control" style="padding-left: 2.5rem;" placeholder="Business contact number">
                        </div>
                      </div>
                      <div class="mb-3">
                        <label class="form-label">Business Address</label>
                        <div class="position-relative">
                          <i class="bi bi-geo-alt input-icon"></i>
                          <input type="text" name="businessAddress" class="form-control" style="padding-left: 2.5rem;" placeholder="Business address">
                        </div>
                      </div>
                      <div class="mb-3">
                        <label class="form-label">Government ID Type</label>
                        <div class="position-relative">
                          <i class="bi bi-card-text input-icon"></i>
                          <select name="govIdType" class="form-select" style="padding-left: 2.5rem;">
                            <option value="">Select ID Type</option>
                            <option value="passport">Passport</option>
                            <option value="drivers">Driver's License</option>
                            <option value="sss">SSS ID</option>
                            <option value="gsis">GSIS ID</option>
                            <option value="postal">Postal ID</option>
                            <option value="tin">TIN ID</option>
                            <option value="philhealth">PhilHealth ID</option>
                            <option value="voters">Voter's ID</option>
                            <option value="prc">PRC ID</option>
                          </select>
                        </div>
                      </div>
                      <div class="mb-3">
                        <label class="form-label">Government ID Number</label>
                        <div class="position-relative">
                          <i class="bi bi-card-heading input-icon"></i>
                          <input type="text" name="govIdNumber" class="form-control" style="padding-left: 2.5rem;" placeholder="ID number">
                        </div>
                      </div>
                      <div class="mb-3">
                        <label class="form-label">Upload Valid ID (Image)</label>
                        <div class="position-relative">
                          <i class="bi bi-image input-icon"></i>
                          <input type="file" name="govIdImage" class="form-control" style="padding-left: 2.5rem;" accept="image/*">
                        </div>
                      </div>
                    </div>

                    <!-- Password Fields -->
                    <div class="form-row mb-3">
                      <div>
                        <label class="form-label">Password</label>
                        <div class="position-relative">
                          <i class="bi bi-lock input-icon"></i>
                          <input type="password" name="password" class="form-control" style="padding-left: 2.5rem;" placeholder="Create password" required>
                        </div>
                      </div>
                      <div>
                        <label class="form-label">Confirm Password</label>
                        <div class="position-relative">
                          <i class="bi bi-lock input-icon"></i>
                          <input type="password" name="confirmPassword" class="form-control" style="padding-left: 2.5rem;" placeholder="Confirm password" required>
                        </div>
                      </div>
                    </div>
                    <button type="submit" class="btn btn-primary w-100 mt-3">Create Account</button>
                  </form>
                </div>
              </div>
            </div>
            <div class="auth-image-panel">
              <div class="auth-image-overlay"></div>
              <div class="auth-image-content">
                <h3 class="auth-logo">Tuklas Nasugbu</h3>
                <p class="auth-description">Join our community and get the most out of your Nasugbu adventure.</p>
                <ul class="auth-benefits mt-3">
                    <li><i class="bi bi-check-lg"></i> Enjoy pristine beaches</li>
                    <li><i class="bi bi-check-lg"></i> Island hopping & snorkeling</li>
                    <li><i class="bi bi-check-lg"></i> Local food & cultural experiences</li>
                    <li><i class="bi bi-check-lg"></i> Save favorites and book activities</li>
                </ul>
                <div class="mt-4"><a href="#contact" class="btn" data-bs-dismiss="modal" style="background:#fff;color:var(--deep-ocean);border-radius:50px;padding:.5rem 1.25rem;">Plan Your Visit</a></div>
             </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Hero slideshow
            let currentSlide = 0;
            const slides = document.querySelectorAll('.hero-slide');
            const totalSlides = slides.length;
            
            function nextSlide() {
                if (slides.length > 0) {
                    slides[currentSlide].classList.remove('active');
                    currentSlide = (currentSlide + 1) % totalSlides;
                    slides[currentSlide].classList.add('active');
                }
            }
            setInterval(nextSlide, 5000);
            
            // Navbar scroll effect
            const navbar = document.getElementById('mainNav');
            const updateNavbarVisibility = () => {
                if (window.scrollY > 60) navbar.classList.add('scrolled');
                else navbar.classList.remove('scrolled');
            };
            window.addEventListener('scroll', updateNavbarVisibility);
            updateNavbarVisibility();

            // Active nav-link on scroll
            const sections = document.querySelectorAll('section[id]');
            const navLi = document.querySelectorAll('.navbar-nav .nav-item .nav-link');
            
            window.addEventListener('scroll', () => {
                let current = '';
                sections.forEach(section => {
                    const sectionTop = section.offsetTop;
                    const sectionHeight = section.clientHeight;
                    if (pageYOffset >= (sectionTop - sectionHeight / 3)) {
                        current = section.getAttribute('id');
                    }
                });

                navLi.forEach(a => {
                    a.classList.remove('active');
                    if (a.getAttribute('href') == '#' + current) {
                        a.classList.add('active');
                    }
                });
            });

            // Close mobile nav on link click
            const navLinks = document.querySelectorAll('.nav-link');
            const menuToggle = document.getElementById('navbarNav');
            const bsCollapse = new bootstrap.Collapse(menuToggle, {toggle: false});
            navLinks.forEach((l) => {
                l.addEventListener('click', () => {
                    if (menuToggle.classList.contains('show')) {
                        bsCollapse.toggle();
                    }
                });
            });
        });

        // Set auth tab
        function setAuthTab(tab) {
            const signupTab = document.getElementById('signup-tab');
            const loginTab = document.getElementById('login-tab');
            if (tab === 'signup' && signupTab) {
                signupTab.click();
            } else if (loginTab) {
                loginTab.click();
            }
        }

        // Toggle signup fields by role
        function onSignupRoleChange() {
            const role = document.getElementById('signupRole').value;
            const touristFields = document.getElementById('touristFields');
            const ownerFields = document.getElementById('ownerFields');
            
            if (touristFields) {
                touristFields.style.display = role === 'tourist' ? 'block' : 'none';
                const touristInputs = touristFields.querySelectorAll('input');
                touristInputs.forEach(input => {
                    input.required = role === 'tourist';
                });
            }
            
            if (ownerFields) {
                ownerFields.style.display = role === 'Spot Owner' ? 'block' : 'none';
                const ownerInputs = ownerFields.querySelectorAll('input, select');
                ownerInputs.forEach(input => {
                    input.required = role === 'Spot Owner';
                });
            }
        }

        // Handle login
        function handleLogin(event) {
            event.preventDefault();
            const form = event.target;
            console.log('Login submitted:', {
                email: form.email.value,
                password: form.password.value
            });
            // Example: Close modal on success
            const authModal = bootstrap.Modal.getInstance(document.getElementById('authModal'));
            authModal.hide();
            alert('Login successful!');
        }

        // legacy placeholder removed; real handler defined below
    </script>


  <script>
const loginForm = document.getElementById('loginForm');

if (loginForm) {
  loginForm.addEventListener('submit', function(e) {
    e.preventDefault();

    Swal.fire({
      title: 'Logging in...',
      text: 'Please wait while we verify your credentials.',
      allowOutsideClick: false,
      didOpen: () => {
        Swal.showLoading();
      }
    });

    fetch('<?= base_url("/users/login") ?>', {
      method: 'POST',
      body: new FormData(loginForm)
    })
    .then(response => response.json())
    .then(data => {
      if (data.status === 'success') {
        Swal.fire({
          icon: 'success',
          title: 'Welcome!',
          text: data.message,
          confirmButtonText: 'OK'
        }).then(() => {
          window.location.href = data.redirect; // ✅ Go to dashboard
        });
      } else {
        Swal.fire({
          icon: 'error',
          title: 'Oops...',
          text: data.message
        });
      }
    })
    .catch(error => {
      Swal.fire({
        icon: 'error',
        title: 'Error!',
        text: 'Something went wrong. Please try again.'
      });
      console.error(error);
    });
  });
}


// Waiting modal HTML injected dynamically (keeps markup minimal here)
let verifyModal, verifyModalEl, verifyCountdownTimer, verifyPollTimer, verifySeconds = 60, verifyEmail = '';

function openVerifyModal(email) {
  verifyEmail = email;
  // Build modal if not present
  if (!document.getElementById('verifyWaitModal')) {
    const modalHtml = `
    <div class="modal fade" id="verifyWaitModal" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Check your Gmail</h5>
          </div>
          <div class="modal-body text-center p-4">
            <div class="mb-3">
              <div class="spinner-border text-primary" role="status"></div>
            </div>
            <p class="mb-1">We sent a verification link to</p>
            <p class="fw-semibold">${email}</p>
            <p class="text-muted small">Please click the Verify button in the email to complete your registration.</p>
            <div class="mt-3">
              <button id="resendBtn" class="btn btn-outline-primary" type="button" disabled>Resend (60s)</button>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          </div>
        </div>
      </div>
    </div>`;
    document.body.insertAdjacentHTML('beforeend', modalHtml);
    verifyModalEl = document.getElementById('verifyWaitModal');
    verifyModal = new bootstrap.Modal(verifyModalEl, { backdrop: 'static', keyboard: false });
    verifyModalEl.addEventListener('hidden.bs.modal', () => {
      clearInterval(verifyCountdownTimer);
      clearInterval(verifyPollTimer);
      verifyCountdownTimer = null;
      verifyPollTimer = null;
    });
  } else {
    // Update email text if reused
    verifyModalEl.querySelector('.modal-body .fw-semibold').textContent = email;
  }

  // Hook resend click
  const resendBtn = document.getElementById('resendBtn');
  resendBtn.onclick = onResendClicked;

  // Start countdown + polling
  verifySeconds = 60;
  updateResendButton();
  if (verifyCountdownTimer) clearInterval(verifyCountdownTimer);
  verifyCountdownTimer = setInterval(() => {
    verifySeconds -= 1;
    if (verifySeconds <= 0) {
      verifySeconds = 0;
      clearInterval(verifyCountdownTimer);
    }
    updateResendButton();
  }, 1000);

  if (verifyPollTimer) clearInterval(verifyPollTimer);
  verifyPollTimer = setInterval(checkVerificationStatus, 5000);

  verifyModal.show();
}

function updateResendButton() {
  const btn = document.getElementById('resendBtn');
  if (!btn) return;
  if (verifySeconds > 0) {
    btn.disabled = true;
    btn.textContent = `Resend (${verifySeconds}s)`;
  } else {
    btn.disabled = false;
    btn.textContent = 'Resend verification';
  }
}

function onResendClicked() {
  if (verifySeconds > 0) return;
  const fd = new FormData();
  fd.append('email', verifyEmail);
  fetch('<?= base_url('verify-email/resend') ?>', { method: 'POST', body: fd })
    .then(r => r.json())
    .then(data => {
      if (data.status === 'resent') {
        verifySeconds = parseInt(data.waitSeconds || 60, 10);
        if (isNaN(verifySeconds) || verifySeconds < 0) verifySeconds = 60;
        if (verifyCountdownTimer) clearInterval(verifyCountdownTimer);
        updateResendButton();
        verifyCountdownTimer = setInterval(() => {
          verifySeconds -= 1;
          if (verifySeconds <= 0) {
            verifySeconds = 0;
            clearInterval(verifyCountdownTimer);
          }
          updateResendButton();
        }, 1000);
        Swal.fire({ icon: 'success', title: 'Sent', text: 'Verification email resent.' , timer: 1500, showConfirmButton: false});
      } else if (data.status === 'wait') {
        // Sync with server-side throttle if out of sync
        verifySeconds = Math.max(0, parseInt(data.waitSeconds || 60, 10));
        updateResendButton();
      } else {
        Swal.fire({ icon: 'error', title: 'Could not resend', text: data.message || 'Please try again later.' });
      }
    })
    .catch(() => Swal.fire({ icon: 'error', title: 'Network error', text: 'Please try again.' }));
}

function checkVerificationStatus() {
  if (!verifyEmail) return;
  fetch(`<?= base_url('verify-email/status') ?>?email=${encodeURIComponent(verifyEmail)}`)
    .then(r => r.json())
    .then(data => {
      if (data && data.status === 'ok' && data.verified) {
        clearInterval(verifyPollTimer);
        clearInterval(verifyCountdownTimer);
        verifyPollTimer = null;
        verifyCountdownTimer = null;
        // Close modal and notify
        const modalInst = bootstrap.Modal.getInstance(document.getElementById('verifyWaitModal'));
        if (modalInst) modalInst.hide();
        Swal.fire({ icon: 'success', title: 'Email verified!', text: 'Your account has been created. You can now log in.' })
          .then(() => { setAuthTab('login'); });
      }
    })
    .catch(() => {/* ignore transient errors */});
}

function handleSignup(event) {
    event.preventDefault();

    const form = document.getElementById('signupForm');
    const fd = new FormData(form);

    // Client-side guard on password match
    if (fd.get('password') !== fd.get('confirmPassword')) {
      Swal.fire({ icon: 'error', title: 'Passwords do not match' });
      return;
    }

    Swal.fire({ title: 'Creating account...', allowOutsideClick: false, didOpen: () => Swal.showLoading() });

    fetch('<?= base_url('signup/submit') ?>', { method: 'POST', body: fd })
      .then(res => res.json())
      .then(data => {
        Swal.close();
        if (data.status === 'verification_sent') {
          // Show waiting modal and begin polling
          const email = fd.get('email');
          // Hide auth modal if open
          const auth = bootstrap.Modal.getInstance(document.getElementById('authModal'));
          if (auth) auth.hide();
          openVerifyModal(email);
        } else if (data.status === 'success') {
          Swal.fire({ icon: 'success', title: 'Success!', text: data.message })
            .then(() => window.location.href = '/');
        } else {
          Swal.fire({ icon: 'error', title: 'Oops!', text: data.message || 'Unable to create account.' });
        }
      })
      .catch(() => {
        Swal.close();
        Swal.fire({ icon: 'error', title: 'Network error', text: 'Please try again.' });
      });
}


</script>

<script>
 // assets/js/attractions-ajax.js
// Live-load top attractions (by view logs) and log views when users click a card.

(function () {
  'use strict';

  // BASE_URL must be defined in page (see HTML snippet). Fallback to '/' if missing.
  const BASE = (typeof BASE_URL !== 'undefined' ? BASE_URL.replace(/\/+$/, '') + '/' : '/');
  const API_TOP = BASE + 'api/attractions/top/6';
  const API_LOG = BASE + 'api/attractions/view';
  const UPLOADS = BASE + 'uploads/spots/';
  const FALLBACK = UPLOADS + 'Spot-No-Image.png';

  function esc(s) {
    if (s === null || s === undefined) return '';
    return String(s)
      .replace(/&/g, '&amp;')
      .replace(/</g, '&lt;')
      .replace(/>/g, '&gt;')
      .replace(/"/g, '&quot;')
      .replace(/'/g, '&#39;');
  }

  async function loadTopSpots() {
    try {
      const res = await fetch(API_TOP, { credentials: 'same-origin' });
      if (!res.ok) throw new Error('Network response not ok: ' + res.status);
      const payload = await res.json();
      if (!payload || !payload.success) throw new Error('API returned error');
      renderSpots(payload.data || []);
    } catch (err) {
      console.error('Failed to load top spots', err);
      // Keep static markup if present or show friendly message
      const container = document.getElementById('attractionsGrid');
      if (container && container.children.length === 0) {
        container.innerHTML = '<div class="col-12 text-center p-4 text-muted">Unable to load attractions.</div>';
      }
    }
  }

  function renderSpots(spots) {
    const container = document.getElementById('attractionsGrid');
    if (!container) return;

    if (!Array.isArray(spots) || spots.length === 0) {
      container.innerHTML = '<div class="col-12 text-center p-4 text-muted">No attractions found.</div>';
      return;
    }

    const html = spots.map(s => {
      const imgSrc = s.primary_image ? (UPLOADS + encodeURIComponent(s.primary_image)) : FALLBACK;
      const shortDesc = s.short_description ? esc(s.short_description) : '';
      return `
        <div class="col-md-6 col-lg-4">
          <div class="attraction-card" data-spot-id="${esc(s.spot_id)}" role="button" tabindex="0">
            <div style="overflow: hidden;">
              <img src="${imgSrc}" onerror="this.onerror=null;this.src='${FALLBACK}';" alt="${esc(s.spot_name)}" class="attraction-image">
            </div>
            <div class="attraction-body">
              <h3 class="attraction-title">${esc(s.spot_name)}</h3>
              <div class="attraction-location"><i class="bi bi-geo-alt-fill"></i><span>${esc(s.location)}</span></div>
              <p class="attraction-description">${shortDesc}</p>
              <div class="d-flex justify-content-between align-items-center mt-2">
                <small class="text-muted">${esc(s.category)}</small>
                <small class="text-muted"><i class="bi bi-eye"></i> ${Number(s.views ?? 0)}</small>
              </div>
            </div>
          </div>
        </div>
      `;
    }).join('');

    container.innerHTML = html;

    // Attach handlers: log view on click or keyboard (Enter / Space)
    container.querySelectorAll('.attraction-card').forEach(card => {
      const sendLog = () => {
        const spotId = card.getAttribute('data-spot-id');
        if (!spotId) return;
        // Fire-and-forget POST to log endpoint
        fetch(API_LOG, {
          method: 'POST',
          credentials: 'same-origin',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({ spot_id: Number(spotId) })
        }).catch(err => console.warn('Failed to log view', err));
        // Optional: navigate to detail page
        // window.location.href = BASE + 'attractions/view/' + spotId;
      };

      card.addEventListener('click', sendLog);
      card.addEventListener('keydown', (e) => {
        if (e.key === 'Enter' || e.key === ' ') sendLog();
      });
    });
  }

  document.addEventListener('DOMContentLoaded', loadTopSpots);
  window.reloadTopAttractions = loadTopSpots;
})();
  </script>



</body>
</html>