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

        <div class="banner">
            <h1>Quiz (<span></span> Questions)</h1>
        </div>

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

                <div id="" class="quizList__item">
                    <div class="quizList__item__quest">A man and a woman.</div>
                    <div class="quizList__answers">
                        <div class="quizList__answers__item" data-result="yes">Um homem e uma mulher</div>
                        <div class="quizList__answers__item" data-result="no">Uma homem e um mulher</div>
                    </div>
                    <div class="quizList__item__hint">Um for Man and Uma for Woman.</div>
                    <div class="quizList__item__btn">
                        <span class="quizList__item__btn__link">Próxima</span>
                    </div>
                </div>

                <div id="" class="quizList__item">
                    <div class="quizList__item__quest">I have a car.</div>
                    <div class="quizList__answers">
                        <div class="quizList__answers__item" data-result="yes">Eu tenho um carro.</div>
                        <div class="quizList__answers__item" data-result="no">Eu tenho uma carro.</div>
                    </div>
                    <div class="quizList__item__hint">Carro is masculine word, so we use um.</div>
                    <div class="quizList__item__btn">
                        <span class="quizList__item__btn__link">Próxima</span>
                    </div>
                </div>

                <div id="" class="quizList__item">
                    <div class="quizList__item__quest">Um Menino e uma Menina</div>
                    <div class="quizList__answers">
                        <div class="quizList__answers__item" data-result="yes">A boy and a girl.</div>
                        <div class="quizList__answers__item" data-result="no">A girl and a boy.</div>
                    </div>
                    <div class="quizList__item__btn">
                        <span class="quizList__item__btn__link">Próxima</span>
                    </div>
                </div>

                <div id="" class="quizList__item">
                    <div class="quizList__item__quest">An Apple</div>
                    <div class="quizList__answers">
                        <div class="quizList__answers__item" data-result="yes">Uma Maçã</div>
                        <div class="quizList__answers__item" data-result="no">Um Maçã</div>
                    </div>
                    <div class="quizList__item__hint">Maçã is feminine word so we use Uma.</div>
                    <div class="quizList__item__btn">
                        <span class="quizList__item__btn__link">Próxima</span>
                    </div>
                </div>

                <div id="" class="quizList__item">
                    <div class="quizList__item__quest">I drink Milk</div>
                    <div class="quizList__answers">
                        <div class="quizList__answers__item" data-result="yes">Eu bebo leite.</div>
                        <div class="quizList__answers__item" data-result="no">Eu como leite.</div>
                    </div>
                    <div class="quizList__item__btn">
                        <span class="quizList__item__btn__link">Próxima</span>
                    </div>
                </div>

                <div id="" class="quizList__item">
                    <div class="quizList__item__quest">Eu tenho um carro e (a House in PT)</div>
                    <div class="quizList__answers">
                        <div class="quizList__answers__item" data-result="yes">uma casa</div>
                        <div class="quizList__answers__item" data-result="no">um casa</div>
                    </div>
                    <div class="quizList__item__btn">
                        <span class="quizList__item__btn__link">Próxima</span>
                    </div>
                </div>

                <div id="" class="quizList__item">
                    <div class="quizList__item__quest">Ele</div>
                    <div class="quizList__answers">
                        <div class="quizList__answers__item" data-result="yes">He</div>
                        <div class="quizList__answers__item" data-result="no">She</div>
                    </div>
                    <div class="quizList__item__btn">
                        <span class="quizList__item__btn__link">Próxima</span>
                    </div>
                </div>

                <div id="" class="quizList__item">
                    <div class="quizList__item__quest">Ela come</div>
                    <div class="quizList__answers">
                        <div class="quizList__answers__item" data-result="yes">She Eats</div>
                        <div class="quizList__answers__item" data-result="no">He Eats</div>
                    </div>
                    <div class="quizList__item__btn">
                        <span class="quizList__item__btn__link">Próxima</span>
                    </div>
                </div>

                <div id="" class="quizList__item">
                    <div class="quizList__item__quest">(The in PT) ______ gato bebe água.</div>
                    <div class="quizList__answers">
                        <div class="quizList__answers__item" data-result="yes">O gato bebe água.</div>
                        <div class="quizList__answers__item" data-result="no">A gato bebe água.</div>
                    </div>
                    <div class="quizList__item__hint">The cat (Gato is Masculine and Singular, so we use O)</div>
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