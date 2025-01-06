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

        <div class="banner">
            <h1>What did you do last week?</h1>
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
                    <div class="contentBlock__block__title">O que é que fez na semana passada ?</div>
                    <div class="contentBlock__block__text">
                        <p>Semana passada não foi muito special mas felizemente eu fui a casa da minha avó onde é que eu jogei (jogar) com os amigos. e visitei (visitar)alguma lugar que nunca fui. Eu e a minha avó fomos (ir) ao restaurante para almoçar ao meio dia. E quando nos voltamos (voltar) à casa. éram (ser) 6 horas à tarde E a minha avó começou (comecar) preparar a comida enquanto eu fui (ir) ao supermercado para fazer as compras que necessidade em casa. Depois disso, nos jantamos juntos às 9 horas à tarde. E fomos (ir) à cama para dormir.</p>
                    </div>
                </div>
                <div id="en-block" class="contentBlock__block hidden">
                    <div class="contentBlock__block__title">What did you do last week?</div>
                    <div class="contentBlock__block__text">
                        <p>Last week wasn't very special but luckily I went to my grandmother's house where I played (play) with friends. and I visited (visit) some place I've never been to. My grandmother and I went to the restaurant for lunch at noon. And when we turn (return) to the house. it was (be) 6 o'clock in the afternoon And my grandmother started (start) preparing the food while I went (go) to the supermarket to do the shopping I needed at home. After that, we had dinner together at 9 o'clock in the afternoon. And we (go) to bed to sleep.</p>
                    </div>
                </div>
           </div>
            
        </div>

        <script src="main.js?v=<?php echo date('mdHi') ?>"></script>

    </body>
</html>