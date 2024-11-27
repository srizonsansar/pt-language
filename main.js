$('.menu__word').on('click', function() {
    $(this).toggleClass('active')
    $(this).next().slideToggle()
})

$('.menu-toggle__item').on('click', function(){
    let selectedLang = $(this).data('lang')
    $('.contentBlock__block').hide()
    $('.contentBlock__block').removeClass('hidden')
    $(this).addClass('active')
    $(this).siblings().removeClass('active')
    if(selectedLang == 'all'){
        $('.contentBlock__block').fadeIn()
        $('.contentBlock').addClass('both')
    } else {
        $('#' + selectedLang + '-block').fadeIn()
        $('.contentBlock').removeClass('both')
    }
    
})