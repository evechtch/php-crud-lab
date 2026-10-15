<?php
require_once 'config/db.php';

// Student profile (starter table)
$profile = null;
$sql = "SELECT * FROM profile LIMIT 1";
$result = mysqli_query($conn, $sql);
if ($result && mysqli_num_rows($result) > 0) {
    $profile = mysqli_fetch_assoc($result);
}

// Education (starter table)
$educations = [];
$sql = "SELECT * FROM education WHERE profile_id = 1 ORDER BY start_year DESC";
$result = mysqli_query($conn, $sql);
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $educations[] = $row;
    }
}

// Part 5: อ่านข้อมูลจากตาราง skill ด้วย mysqli_query() และ mysqli_fetch_assoc()
$skills = [];
$sql = "SELECT * FROM skill WHERE profile_id = 1";
$result = mysqli_query($conn, $sql);
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $skills[] = $row;
    }
}

// Part 5: อ่านข้อมูลจากตาราง training ด้วย mysqli_query() และ mysqli_fetch_assoc()
$trainings = [];
$sql = "SELECT * FROM training WHERE profile_id = 1 ORDER BY training_year DESC";
$result = mysqli_query($conn, $sql);
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $trainings[] = $row;
    }
}

// Part 6: อ่านข้อมูลจากตาราง interest และ social_link
// ใช้ try/catch เผื่อยังไม่ได้สร้างตาราง หน้าเว็บจะได้ไม่ล่ม
$interests = [];
$social_links = [];
try {
    $sql = "SELECT * FROM interest WHERE profile_id = 1 ORDER BY interest_id ASC";
    $result = mysqli_query($conn, $sql);
    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $interests[] = $row;
        }
    }

    $sql = "SELECT * FROM social_link WHERE profile_id = 1 ORDER BY sort_order ASC, link_id ASC";
    $result = mysqli_query($conn, $sql);
    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $social_links[] = $row;
        }
    }
} catch (mysqli_sql_exception $e) {
    // ตารางยังไม่มี -> ปล่อยเป็น array ว่าง
}
?>
<!doctype html>
<html lang="th">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo $profile ? htmlspecialchars($profile['full_name']) : 'My AI Portfolio'; ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Mono:wght@400;500&family=Space+Grotesk:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
</head>

<body>
    <div class="noise"></div>
    <div class="cursor-glow"></div>

    <header class="site-nav">
        <a class="brand" href="#top">
            <span class="brand-dot"></span>
            <?php echo $profile ? htmlspecialchars($profile['full_name']) : 'AI PORTFOLIO'; ?>
        </a>

        <nav>
            <a href="#about">About</a>
            <a href="#education">Education</a>
            <a href="#skills">Skills</a>
            <a href="#training">Training</a>
            <a href="#interests">Interests</a>
        </nav>

        <?php if ($profile): ?>
            <a class="nav-cta" href="mailto:<?php echo htmlspecialchars($profile['email']); ?>">LET'S TALK ↗</a>
        <?php else: ?>
            <a class="nav-cta" href="#about">EXPLORE ↗</a>
        <?php endif; ?>
    </header>

    <main class="container" id="top">

        <section class="hero">
            <div class="hero-grid"></div>

            <div class="hero-copy">
                <div class="eyebrow">
                    <span class="status-dot"></span>
                    AVAILABLE TO LEARN • BUILD • CREATE
                </div>

                <p class="mini-label">01 / INTRO</p>

                <h1>
                    <?php echo $profile ? htmlspecialchars($profile['full_name']) : 'YOUR NAME'; ?>
                </h1>

                <p class="headline">
                    <?php echo $profile ? htmlspecialchars($profile['headline']) : 'Applied AI & Intelligent Technology Student'; ?>
                </p>

                <div class="hero-actions">
                    <a class="primary-btn" href="#skills">VIEW MY SKILLS <span>↗</span></a>
                    <a class="ghost-btn" href="#about">ABOUT ME</a>
                </div>

                <div class="contact-strip">
                    <?php if ($profile): ?>
                        <span>✉ <?php echo htmlspecialchars($profile['email']); ?></span>
                        <span>⌁ <?php echo htmlspecialchars($profile['phone']); ?></span>
                        <span>⌖ <?php echo htmlspecialchars($profile['address']); ?></span>
                    <?php endif; ?>
                </div>
            </div>

            <div class="hero-visual">
                <div class="orbit orbit-one"></div>
                <div class="orbit orbit-two"></div>
                <div class="visual-tag tag-top">AI / STUDENT</div>
                <div class="visual-tag tag-bottom">100% CURIOUS</div>

                <div class="photo-frame">
                    <div class="photo-number">#001</div>
                    <img class="avatar" src="assets/images/<?php echo $profile ? htmlspecialchars($profile['photo']) : 'student.jpg'; ?>" alt="Profile Photo">
                    <div class="scan-line"></div>
                </div>
            </div>
        </section>

        <div class="ticker" aria-label="skills ticker">
            <div class="ticker-track">
                <span>ARTIFICIAL INTELLIGENCE</span><b>✦</b>
                <span>PYTHON</span><b>✦</b>
                <span>DATABASE</span><b>✦</b>
                <span>IoT</span><b>✦</b>
                <span>CREATIVE TECHNOLOGY</span><b>✦</b>
                <span>ARTIFICIAL INTELLIGENCE</span><b>✦</b>
                <span>PYTHON</span><b>✦</b>
                <span>DATABASE</span><b>✦</b>
                <span>IoT</span><b>✦</b>
            </div>
        </div>

        <section class="card about-card" id="about">
            <div class="section-meta">02 / ABOUT</div>
            <div class="section-heading">
                <h2>ABOUT <em>ME</em></h2>
                <span class="big-symbol">✦</span>
            </div>
            <p class="about-text">
                <?php echo $profile ? nl2br(htmlspecialchars($profile['about_me'])) : 'Add your profile data in phpMyAdmin.'; ?>
            </p>
        </section>

        <section class="card" id="education">
            <div class="section-meta">03 / EDUCATION</div>
            <div class="section-heading">
                <h2>MY <em>JOURNEY</em></h2>
                <span class="section-note">LEARNING IN PUBLIC</span>
            </div>

            <div class="timeline">
                <?php if (count($educations) > 0): ?>
                    <?php foreach ($educations as $edu): ?>
                        <article class="timeline-item">
                            <div class="timeline-year">
                                <?php echo htmlspecialchars($edu['start_year']); ?> — <?php echo htmlspecialchars($edu['end_year']); ?>
                            </div>
                            <div class="timeline-dot"></div>
                            <div class="timeline-content">
                                <span class="item-index">EDUCATION</span>
                                <h3><?php echo htmlspecialchars($edu['degree']); ?></h3>
                                <p><?php echo htmlspecialchars($edu['institution']); ?></p>
                            </div>
                        </article>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p class="empty-state">No education data found.</p>
                <?php endif; ?>
            </div>
        </section>

        <section class="card" id="skills">
            <div class="section-meta">04 / SKILLS</div>
            <div class="section-heading">
                <h2>TOOLS I <em>USE</em></h2>
                <span class="section-note">BUILD / TEST / REPEAT</span>
            </div>

            <div class="skills">
                <?php foreach ($skills as $index => $skill): ?>
                    <div class="skill-badge">
                        <div class="skill-top">
                            <span class="skill-number"><?php echo str_pad($index + 1, 2, '0', STR_PAD_LEFT); ?></span>
                            <span class="skill-arrow">↗</span>
                        </div>
                        <strong><?php echo htmlspecialchars($skill['skill_name']); ?></strong>
                        <span><?php echo htmlspecialchars($skill['skill_level']); ?></span>
                        <div class="skill-line"><i></i></div>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>

        <section class="card" id="training">
            <div class="section-meta">05 / TRAINING</div>
            <div class="section-heading">
                <h2>LEARNING <em>MODE</em></h2>
                <span class="section-note">CERTS / TRAINING</span>
            </div>

            <div class="training-list">
                <?php foreach ($trainings as $index => $training): ?>
                    <article class="training-item">
                        <span class="training-number"><?php echo str_pad($index + 1, 2, '0', STR_PAD_LEFT); ?></span>
                        <div>
                            <h3><?php echo htmlspecialchars($training['course_name']); ?></h3>
                            <p><?php echo htmlspecialchars($training['organization']); ?> <span>•</span> <?php echo htmlspecialchars($training['training_year']); ?></p>
                        </div>
                        <span class="training-arrow">↗</span>
                    </article>
                <?php endforeach; ?>
            </div>
        </section>

        <section class="card" id="interests">
            <div class="section-meta">06 / INTERESTS</div>
            <div class="section-heading">
                <h2>THINGS I <em>LOVE</em></h2>
                <span class="section-note">OFF-SCREEN VIBES</span>
            </div>

            <div class="interest-grid">
                <?php if (count($interests) > 0): ?>
                    <?php foreach ($interests as $interest): ?>
                        <div class="interest-card">
                            <span class="interest-icon"><?php echo htmlspecialchars($interest['icon'] ?: '✦'); ?></span>
                            <h3><?php echo htmlspecialchars($interest['interest_name']); ?></h3>
                            <p><?php echo htmlspecialchars($interest['description']); ?></p>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p class="empty-state">No interests added yet.</p>
                <?php endif; ?>
            </div>
        </section>

        <section class="contact-card">
            <div>
                <span class="section-meta">07 / CONTACT</span>
                <h2>LET'S BUILD<br><em>SOMETHING.</em></h2>
            </div>
            <div class="contact-side">
                <p>Got an idea, project, or just want to say hi?</p>
                <?php if ($profile): ?>
                    <a class="primary-btn" href="mailto:<?php echo htmlspecialchars($profile['email']); ?>">SEND A MESSAGE ↗</a>
                <?php endif; ?>

                <?php if (count($social_links) > 0): ?>
                    <div class="social-links">
                        <?php foreach ($social_links as $link): ?>
                            <a class="social-chip" href="<?php echo htmlspecialchars($link['url']); ?>" target="_blank" rel="noopener noreferrer">
                                <strong><?php echo htmlspecialchars($link['platform']); ?></strong>
                                <?php if (!empty($link['display_name'])): ?>
                                    <span><?php echo htmlspecialchars($link['display_name']); ?></span>
                                <?php endif; ?>
                            </a>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </section>

        <footer>
            <span>© <?php echo date('Y'); ?> <?php echo $profile ? htmlspecialchars($profile['full_name']) : 'AI STUDENT'; ?></span>
            <span>BUILT WITH PHP + MYSQL + CURIOSITY</span>
            <a href="#top">BACK TO TOP ↑</a>
        </footer>
    </main>

    <script>
        const glow = document.querySelector('.cursor-glow');

        window.addEventListener('pointermove', (e) => {
            glow.style.left = e.clientX + 'px';
            glow.style.top = e.clientY + 'px';
        });

        const revealItems = document.querySelectorAll('.card, .contact-card, .hero-copy, .hero-visual');

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('is-visible');
                }
            });
        }, { threshold: 0.12 });

        revealItems.forEach(item => {
            item.classList.add('reveal');
            observer.observe(item);
        });
    </script>
</body>
</html>
<?php mysqli_close($conn); ?>