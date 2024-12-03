<div class="menu">
    <div class="menu__word">
        <div class="menu__word__text">Menu</div>
        <div class="menu__word__lines">
            <span></span>
            <span></span>
            <span></span>
        </div>
    </div>
    <div class="menu__dropdown">
        <a href="/pt" class="menu__dropdown__link <?php if($active == 'home-page') { ?>active<?php } ?>">Basic Words</a>
        <a href="introduction" class="menu__dropdown__link <?php if($active == 'intro-page') { ?>active<?php } ?>">Introduction</a>
        <a href="timepass" class="menu__dropdown__link <?php if($active == 'time-pass') { ?>active<?php } ?>">Favorite Time Pass</a>
        <a href="last-week" class="menu__dropdown__link <?php if($active == 'last-week') { ?>active<?php } ?>">Last Week</a>
        <a href="quiz" class="menu__dropdown__link <?php if($active == 'quiz-page') { ?>active<?php } ?>">Quiz Page</a>
    </div>
</div>