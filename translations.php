<?php $active = 'translations'; ?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>Translations : Learning basis Portuguese Language</title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="style.css?v=<?php echo date('mdHi') ?>">
        <script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
    </head>
    <body>

        <div class="banner">
            <h1>Translation of day-to-day Portuguese sentences.</h1>
        </div>

        <?php include('nav.php') ?>

        <div class="container">

            <div class="translationList">

                <div class="translationList__item">
                    <div class="translationList__item__content translationList__item__content--pt">Eu sou do Nepal</div>
                    <div class="translationList__item__content translationList__item__content--en">I am from Nepal</div>
                </div>

                <div class="translationList__item">
                    <div class="translationList__item__content translationList__item__content--pt">Eu sou o professor</div>
                    <div class="translationList__item__content translationList__item__content--en">I am a teacher</div>
                </div>

                <div class="translationList__item">
                    <div class="translationList__item__content translationList__item__content--pt">Eu sou muito baixo</div>
                    <div class="translationList__item__content translationList__item__content--en">I am very short</div>
                </div>

                <div class="translationList__item">
                    <div class="translationList__item__content translationList__item__content--pt">Eu estou em casa</div>
                    <div class="translationList__item__content translationList__item__content--en">I am at home</div>
                </div>

                <div class="translationList__item">
                    <div class="translationList__item__content translationList__item__content--pt">O copo está na mesa</div>
                    <div class="translationList__item__content translationList__item__content--en">The cup is on the table</div>
                </div>

                <div class="translationList__item">
                    <div class="translationList__item__content translationList__item__content--pt">O que é que está a fazer ?</div>
                    <div class="translationList__item__content translationList__item__content--en">What are you doing ?</div>
                </div>

                <div class="translationList__item">
                    <div class="translationList__item__content translationList__item__content--pt">Como está ?</div>
                    <div class="translationList__item__content translationList__item__content--en">How are you ?</div>
                </div>

            </div>

            <div class="translationList__next">
                <div class="translationList__next__link">Next</div>
            </div>
            
        </div>

        <script src="main.js?v=<?php echo date('mdHi') ?>"></script>
        <script src="translation.js?v=<?php echo date('mdHi') ?>"></script>

    </body>
</html>