<?php $active = 'last-week'; ?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>What did you do last week? : Learning basis Portuguese Language</title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="style.css?v=<?php echo date('mdHi') ?>">
        <script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
    </head>
    <body>

        <h1>What did you do last week?</h1>

        <?php include('nav.php') ?>

        <div class="container">

           <div class="menu-toggle">
                <div class="menu-toggle__item active" data-lang="pt">PT</div>
                <div class="menu-toggle__item" data-lang="en">EN</div>
                <div class="menu-toggle__item" data-lang="all">Both</div>
           </div>

           <div class="contentBlock">
                <div id="pt-block" class="contentBlock__block">
                    <div class="contentBlock__block__title">O que voce fez na semana passada?</div>
                    <div class="contentBlock__block__text">
                        <p>Na semana passada fui visitar um amigo que vive no Porto com a família. Tem um apartamento muito bonito perto do centro da cidade. A família dele é muito simpática, cozinharam-me um prato tradicional português. Damos uma volta pela cidade do Porto. A cidade é muito bonita, limpa e o tempo também estava limpo e soalheiro. Definitivamente visitarei o Porto novamente.</p>
                    </div>
                </div>
                <div id="en-block" class="contentBlock__block hidden">
                    <div class="contentBlock__block__title">What did you do last week?</div>
                    <div class="contentBlock__block__text">
                        <p>Last week I went to visit a friend who lives in Porto with his family. He has a very nice apartment near the city center. His family is very friendly, they cooked traditional Portuguese dish for me. We take a round of the Porto city. The city is very beautiful, clean and weather was also bright and sunny. I will definetly visit Porto again.</p>
                    </div>
                </div>
           </div>
            
        </div>

        <script src="main.js?v=<?php echo date('mdHi') ?>"></script>

    </body>
</html>