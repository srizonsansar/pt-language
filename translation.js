$(document).ready(function() {
    // Get the number of items
    let noOfItems = $('.translationList__item').length;
    let currentItem = 1; // Track the current item to show

    // Update the count display
    $('.translationCount span').text(noOfItems);

    // Generate an array of numbers from 1 to noOfItems
    let numbers = Array.from({length: noOfItems}, (_, i) => i + 1);

    // Shuffle the array to get random order
    for (let i = numbers.length - 1; i > 0; i--) {
        const j = Math.floor(Math.random() * (i + 1));
        [numbers[i], numbers[j]] = [numbers[j], numbers[i]];
    }

    // Assign the random numbers as IDs and set CSS order
    $('.translationList__item').each(function(index) {
        $(this).attr('id', 'item-' + numbers[index])
               .css('order', numbers[index]);
    });

    // Show item with ID 1 by default
    $('#item-1').show();

    // Handle next button click
    $('.translationList__next__link').click(function(e) {
        e.preventDefault();
        
        // Increment to next item
        currentItem++;
        
        // If we've reached the end, hide the next button
        if (currentItem > noOfItems) {
            $('.translationList__next').hide();
            return;
        }
        
        // Show the next item
        $('#item-' + currentItem).show();

        // Scroll to bottom
        $('html, body').animate({ scrollTop: $(document).height() - $(window).height() }, 'slow');
    });
    
});