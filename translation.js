$(document).ready(function() {
    // Assign random order values
    $('.translationList__item').each(function() {
        // Generate a random order value
        let randomOrder = Math.floor(Math.random() * 100); // Adjust the range as needed
        // Assign the random order value as a data attribute and CSS order property
        $(this).data('order', randomOrder).css('order', randomOrder);
    });

    // Find the item with the lowest order and display it
    let $firstItem = $('.translationList__item')
        .sort(function(a, b) {
            return $(a).data('order') - $(b).data('order');
        })
        .first();

    $firstItem.show(); // Display the first item
    let currentOrder = $firstItem.data('order'); // Initialize current order

    // Show the next item on button click
    let currentData = 1
    let noOfReading = $('.translationList__item').length
    $('.translationList__next__link').on('click', function() {
        currentData++
        // Find the next hidden item with the lowest order greater than currentOrder
        let $nextItem = $('.translationList__item')
            .filter(function() {
                return $(this).is(':hidden') && $(this).data('order') > currentOrder;
            })
            .sort(function(a, b) {
                return $(a).data('order') - $(b).data('order');
            })
            .first();

        // If an item is found, show it and update currentOrder
        if ($nextItem.length) {
            currentOrder = $nextItem.data('order');
            $nextItem.fadeIn(); // Use fadeIn for a smooth transition
        }


        if(currentData > noOfReading - 1){
            $('.translationList__next').fadeOut()
        }

        $('html, body').animate({ scrollTop: $(document).height() - $(window).height() }, 'slow');

    });

});
