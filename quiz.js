$(document).ready(function () {
    // Select all elements with the class "quizList__item"
    let items = $(".quizList__item");
    let totalItems = items.length;
    $('h1').find('span').text(totalItems)

    // Generate an array of numbers from 0 to totalItems - 1
    let orderArray = Array.from({ length: totalItems }, (_, index) => index);

    // Shuffle the array randomly
    for (let i = orderArray.length - 1; i > 0; i--) {
        let j = Math.floor(Math.random() * (i + 1));
        [orderArray[i], orderArray[j]] = [orderArray[j], orderArray[i]];
    }

    // Assign the shuffled order values to the items
    items.each(function (index) {
        $(this).css("order", orderArray[index]);
        $(this).attr('id', orderArray[index])
        // If the order is 0, show it; otherwise, hide it
        if (orderArray[index] === 0) {
            $(this).show();
        } else {
            $(this).hide();
        }
    });

    $('.quizList__item__btn__link').on('click', function(){
        let currentId = $(this).parent().parent().attr('id')
        currentId++
        $(this).parent().parent().hide()
        $('#' + currentId++).fadeIn()
        if(currentId++ > totalItems){
            $('.thankYouMessage').fadeIn()
        }
    })


    $(".quizList__item").each(function () {
        // Select the .quizList__answers__item within this .quizList__item
        let answers = $(this).find(".quizList__answers__item");
        let totalAnswers = answers.length;

        // Generate an array of numbers from 0 to totalAnswers - 1
        let orderArray = Array.from({ length: totalAnswers }, (_, index) => index);

        // Shuffle the array randomly
        for (let i = orderArray.length - 1; i > 0; i--) {
            let j = Math.floor(Math.random() * (i + 1));
            [orderArray[i], orderArray[j]] = [orderArray[j], orderArray[i]];
        }

        // Assign the shuffled order values to the answers
        answers.each(function (index) {
            $(this).css("order", orderArray[index]); // Assign order
        });
    });

});

let clickCount = 0
let correctAns = 0
$('.quizList__answers__item').on('click', function(){

    clickCount++
    let isCorrectOrNot = $(this).data('result')
    if(isCorrectOrNot == 'yes'){
        $(this).addClass('correct')
        correctAns++
        $('.correctAnsCount .correct').text(correctAns)
    } else {
        $(this).addClass('wrong')
        $(this).siblings().addClass('correct')
    }
    $(this).parent().nextAll().fadeIn()
    $('.correctAnsCount .totalplayed').text(clickCount)

})