$(document).ready(function() {
let door = $('.door');

    door.click(function() {
        door.toggleClass('open');
        if(door.hasClass('open')) {
            door.css('transform', 'translate(-56.5%, -50%) rotateY(20deg) skewY(-10deg)');
        } else {
            door.css('transform', 'translate(-50%, -50%) rotateY(0deg)');
        }
    });
});