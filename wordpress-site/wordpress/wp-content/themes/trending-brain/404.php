<?php get_header(); ?>

<section class="error-page">
    <div class="error-page-inner">

        <canvas id="robot-canvas" width="200" height="240"></canvas>

        <h1>404</h1>
        <p>This page doesn't exist. Maybe it wandered off to train a neural network.</p>

        <div class="error-actions">
            <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="view-all-btn view-all-btn--large">
                Back to Home
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                </svg>
            </a>
            <a href="<?php echo esc_url( home_url( '/articles/' ) ); ?>" class="view-all-btn view-all-btn--large" style="background:transparent;color:var(--black);">
                Browse Articles
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                </svg>
            </a>
        </div>
    </div>
</section>

<script>
(function () {
    var canvas = document.getElementById('robot-canvas');
    if (!canvas) return;
    var ctx = canvas.getContext('2d');
    var px = 8;
    var frame = 0;
    var blink = false;
    var blinkTimer = 0;

    // Mouse tracking
    var mouseX = 0.5; // 0..1 normalized (0.5 = center)
    var mouseY = 0.5;

    document.addEventListener('mousemove', function (e) {
        var rect = canvas.getBoundingClientRect();
        var centerX = rect.left + rect.width / 2;
        var centerY = rect.top + rect.height / 2;
        // Normalize: -1 to 1 range relative to canvas center, clamped
        var dx = (e.clientX - centerX) / (window.innerWidth / 2);
        var dy = (e.clientY - centerY) / (window.innerHeight / 2);
        mouseX = Math.max(-1, Math.min(1, dx));
        mouseY = Math.max(-1, Math.min(1, dy));
    });

    // Base robot (no eyes — we draw those dynamically)
    // 0=empty, 1=dark(body), 2=medium(face), 4=accent, 5=eye socket
    var robot = [
        // Antenna
        [0,0,0,0,0,0,0,0,0,0,0,0,4,0,0,0,0,0,0,0,0,0,0,0,0],
        [0,0,0,0,0,0,0,0,0,0,0,0,4,0,0,0,0,0,0,0,0,0,0,0,0],
        [0,0,0,0,0,0,0,0,0,0,0,4,4,4,0,0,0,0,0,0,0,0,0,0,0],
        // Head top
        [0,0,0,0,0,0,0,1,1,1,1,1,1,1,1,1,1,1,0,0,0,0,0,0,0],
        [0,0,0,0,0,0,1,2,2,2,2,2,2,2,2,2,2,2,1,0,0,0,0,0,0],
        [0,0,0,0,0,0,1,2,2,2,2,2,2,2,2,2,2,2,1,0,0,0,0,0,0],
        // Eyes row — 5 = eye socket (3x3 socket for each eye)
        [0,0,0,0,0,0,1,2,5,5,5,2,2,2,5,5,5,2,1,0,0,0,0,0,0],
        [0,0,0,0,0,0,1,2,5,5,5,2,2,2,5,5,5,2,1,0,0,0,0,0,0],
        [0,0,0,0,0,0,1,2,5,5,5,2,2,2,5,5,5,2,1,0,0,0,0,0,0],
        // Mouth
        [0,0,0,0,0,0,1,2,2,2,2,2,2,2,2,2,2,2,1,0,0,0,0,0,0],
        [0,0,0,0,0,0,1,2,2,2,1,1,1,1,1,2,2,2,1,0,0,0,0,0,0],
        // Head bottom
        [0,0,0,0,0,0,0,1,1,1,1,1,1,1,1,1,1,1,0,0,0,0,0,0,0],
        // Neck
        [0,0,0,0,0,0,0,0,0,0,0,1,1,1,0,0,0,0,0,0,0,0,0,0,0],
        // Body top
        [0,0,0,0,0,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,0,0,0,0,0],
        [0,0,0,0,1,1,2,2,2,2,2,2,2,2,2,2,2,2,2,1,1,0,0,0,0],
        [0,0,0,0,1,2,2,2,2,2,2,2,2,2,2,2,2,2,2,2,1,0,0,0,0],
        // Chest panel
        [0,0,0,0,1,2,2,2,4,4,4,4,4,4,4,4,4,2,2,2,1,0,0,0,0],
        [0,0,0,0,1,2,2,2,4,2,2,2,2,2,2,2,4,2,2,2,1,0,0,0,0],
        [0,0,0,0,1,2,2,2,4,2,2,2,2,2,2,2,4,2,2,2,1,0,0,0,0],
        [0,0,0,0,1,2,2,2,4,4,4,4,4,4,4,4,4,2,2,2,1,0,0,0,0],
        // Body bottom
        [0,0,0,0,1,2,2,2,2,2,2,2,2,2,2,2,2,2,2,2,1,0,0,0,0],
        [0,0,0,0,0,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,0,0,0,0,0],
        // Legs
        [0,0,0,0,0,0,0,0,0,1,1,0,0,0,1,1,0,0,0,0,0,0,0,0,0],
        [0,0,0,0,0,0,0,0,0,1,1,0,0,0,1,1,0,0,0,0,0,0,0,0,0],
        [0,0,0,0,0,0,0,0,0,1,1,0,0,0,1,1,0,0,0,0,0,0,0,0,0],
        // Feet
        [0,0,0,0,0,0,0,0,1,1,1,1,0,1,1,1,1,0,0,0,0,0,0,0,0],
    ];

    var colors = {
        0: null,
        1: '#111111',
        2: '#555555',
        4: '#888888',
        5: '#333333' // eye socket dark bg
    };

    // Eye sockets: top-left corner of each 3x3 socket (col, row)
    var leftEye  = { col: 8, row: 6 };
    var rightEye = { col: 14, row: 6 };

    function drawRobot() {
        ctx.clearRect(0, 0, canvas.width, canvas.height);

        // Vertical offset for content (top padding so antenna doesn't clip)
        var padY = 8;
        var bobY = Math.sin(frame * 0.04) * 4;

        // Draw body
        for (var row = 0; row < robot.length; row++) {
            for (var col = 0; col < robot[row].length; col++) {
                var val = robot[row][col];
                if (val === 0) continue;

                ctx.fillStyle = colors[val];
                ctx.fillRect(col * px, row * px + bobY + padY, px, px);
            }
        }

        // Draw pupils that follow mouse
        // Pupil is 1x1 px inside the 3x3 socket, offset by mouseX/mouseY
        if (!blink) {
            // Smooth 360-degree eye tracking
            var dist = Math.sqrt(mouseX * mouseX + mouseY * mouseY);
            var clampedDist = Math.min(dist, 1);
            var pupilOffX = dist > 0.001 ? (mouseX / dist) * clampedDist : 0;
            var pupilOffY = dist > 0.001 ? (mouseY / dist) * clampedDist : 0;

            // Left eye pupil (center of 3x3 socket is col+1, row+1)
            var lpx = (leftEye.col + 1 + pupilOffX) * px;
            var lpy = (leftEye.row + 1 + pupilOffY) * px + bobY + padY;
            ctx.fillStyle = '#ffffff';
            ctx.fillRect(lpx, lpy, px, px);

            // Right eye pupil
            var rpx = (rightEye.col + 1 + pupilOffX) * px;
            var rpy = (rightEye.row + 1 + pupilOffY) * px + bobY + padY;
            ctx.fillStyle = '#ffffff';
            ctx.fillRect(rpx, rpy, px, px);
        } else {
            // Blink: draw a horizontal line across each eye socket
            ctx.fillStyle = '#777777';
            ctx.fillRect(leftEye.col * px, (leftEye.row + 1) * px + bobY + padY, 3 * px, px);
            ctx.fillRect(rightEye.col * px, (rightEye.row + 1) * px + bobY + padY, 3 * px, px);
        }

        // Antenna light: pulses red
        var pulse = Math.sin(frame * 0.08) * 0.5 + 0.5;
        var r = Math.floor(200 + 55 * pulse);
        ctx.fillStyle = 'rgb(' + r + ',' + Math.floor(50 * pulse) + ',' + Math.floor(50 * pulse) + ')';
        ctx.fillRect(11 * px, 0 * px + bobY + padY, 3 * px, px);

        // Chest panel inner glow
        var glow = Math.sin(frame * 0.06 + 1) * 0.4 + 0.6;
        ctx.fillStyle = 'rgba(100, 100, 100, ' + (glow * 0.3) + ')';
        ctx.fillRect(9 * px, 17 * px + bobY + padY, 7 * px, 2 * px);

        // Shadow beneath robot
        ctx.fillStyle = 'rgba(0, 0, 0, 0.08)';
        var shadowStretch = 1 + Math.sin(frame * 0.04) * 0.1;
        ctx.beginPath();
        ctx.ellipse(
            canvas.width / 2,
            (robot.length) * px + padY + 14,
            56 * shadowStretch,
            4,
            0, 0, Math.PI * 2
        );
        ctx.fill();

        // Blink logic
        blinkTimer++;
        if (!blink && blinkTimer > 80 + Math.random() * 60) {
            blink = true;
            blinkTimer = 0;
        }
        if (blink && blinkTimer > 5) {
            blink = false;
            blinkTimer = 0;
        }

        frame++;
        requestAnimationFrame(drawRobot);
    }

    drawRobot();
})();
</script>

<?php get_footer(); ?>
