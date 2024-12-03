<?php $active = 'quiz-page'; ?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>Quiz : Learning basis Portuguese Language</title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="style.css?v=<?php echo date('mdHi') ?>">
        <script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
    </head>
    <body>

        <h1>Quiz (<span></span> Questions)</h1>

        <?php include('nav.php') ?>

        <div class="correctAnsCount">
            <span class="correct">0</span>&nbsp;Correct Ans Out of&nbsp;<span class="totalplayed">0</span>
        </div>

        <div class="container">

            <div class="quizList">

                <div id="" class="quizList__item">
                    <div class="quizList__item__quest">Eu</div>
                    <div class="quizList__answers">
                        <div class="quizList__answers__item" data-result="yes">I</div>
                        <div class="quizList__answers__item" data-result="no">We</div>
                    </div>
                    <div class="quizList__item__btn">
                        <span class="quizList__item__btn__link">Próxima</span>
                    </div>
                </div>

                <div id="" class="quizList__item">
                    <div class="quizList__item__quest">A girl.</div>
                    <div class="quizList__answers">
                        <div class="quizList__answers__item" data-result="yes">Uma Menina</div>
                        <div class="quizList__answers__item" data-result="no">Um Menina</div>
                    </div>
                    <div class="quizList__item__hint">Girl (Menina) is feminine word, so we use Uma</div>
                    <div class="quizList__item__btn">
                        <span class="quizList__item__btn__link">Próxima</span>
                    </div>
                </div>

            </div>

            <div class="thankYouMessage">Thank you for Playing | Refresh Page to play again.</div>
            
        </div>

        <script src="main.js?v=<?php echo date('mdHi') ?>"></script>
        <script src="quiz.js?v=<?php echo date('mdHi') ?>"></script>

    </body>
</html>