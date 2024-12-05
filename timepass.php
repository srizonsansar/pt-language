<?php $active = 'time-pass'; ?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>Favorite Time Pass : Learning basis Portuguese Language</title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="style.css?v=<?php echo date('mdHi') ?>">
        <script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
    </head>
    <body>

        <div class="banner">
            <h1>Your Favorite Time Pass</h1>
        </div>

        <?php include('nav.php') ?>

        <div class="container">

           <div class="menu-toggle">
                <div class="menu-toggle__item active" data-lang="pt">PT</div>
                <div class="menu-toggle__item" data-lang="en">EN</div>
                <div class="menu-toggle__item" data-lang="all">Both</div>
           </div>

           <div class="contentBlock">
                <div id="pt-block" class="contentBlock__block">
                    <div class="contentBlock__block__title">Qual e o seu passa tempo favorito?</div>
                    <div class="contentBlock__block__text">
                        <p>O meu passatempo favorito é visitar os lugares e diferentecidade. Porque, durante a semnana eu perciso de trabalhar 10 horas por dia e 6 dias per semana. Além disso, eu gosto de ir à praia  e conchecer as culturas porque eu sou uma pessoa que gostava de conchecer as pessoas. Quando eu fiver o timep ficarei na casa pots estoucansado por cause de trabalho e faço a conversa com a minha familia.</p>
                    </div>
                </div>
                <div id="en-block" class="contentBlock__block hidden">
                    <div class="contentBlock__block__title">What is your favorite time pass?</div>
                    <div class="contentBlock__block__text">
                        <p>My favorite pastime is visiting different places and cities. Because, during the week I need to work 10 hours a day and 6 days a week. Besides, I like going to the beach and getting to know cultures because I'm a person who likes to get to know people. When I have time, I'll stay at home because I'm tired because of work and I'll talk to my family.</p>
                    </div>
                </div>
           </div>
            
        </div>

        <script src="main.js?v=<?php echo date('mdHi') ?>"></script>

    </body>
</html>