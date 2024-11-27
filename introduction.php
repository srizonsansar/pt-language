<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>Introduction : Learning basis Portuguese Language</title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="style.css?v=<?php echo date('mdHi') ?>">
        <script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
    </head>
    <body>

        <h1>Self Introduction in Portuguese</h1>

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
                <a href="/pt" class="menu__dropdown__link">Basic Words</a>
                <a href="introduction" class="menu__dropdown__link active">Introduction</a>
                <a href="timepass" class="menu__dropdown__link">Favorite Time Pass</a>
            </div>
        </div>

        <div class="container">

           <div class="menu-toggle">
                <div class="menu-toggle__item active" data-lang="pt">PT</div>
                <div class="menu-toggle__item" data-lang="en">EN</div>
                <div class="menu-toggle__item" data-lang="all">Both</div>
           </div>

           <div class="contentBlock">
                <div id="pt-block" class="contentBlock__block">
                    <div class="contentBlock__block__title">Apresentação</div>
                    <div class="contentBlock__block__text">
                        <p>Eu sou Navaraj Subedi. Sou de Chitwan, Nepal. Tenho 37 anos. A minha profissão é Web Developer. Vivo com a minha família em Amadora. Moro em Portugal há 5 anos.</p>
                    </div>
                </div>
                <div id="en-block" class="contentBlock__block hidden">
                    <div class="contentBlock__block__title">Self Introduction</div>
                    <div class="contentBlock__block__text">
                        <p>I am Navaraj Subedi. I come from Chitwan, Nepal. I am 37 years old. My profession is Web Developer. I live with my family in Amadora. I have been living in Portugal for 5 years.</p>
                    </div>
                </div>
           </div>
            
        </div>

        <script src="main.js?v=<?php echo date('mdHi') ?>"></script>

    </body>
</html>