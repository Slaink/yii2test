$(function () {
    var map;
    ymaps.ready(function(){
        map = new ymaps.Map("map", { center: [55.76, 37.64], zoom: 10 });
    });

    $('body').on('click', '#count_submit', function () {
        var from = $('input[name=from]').val();
        var to = $('input[name=to]').val();
        if(from == '' || to == '') {
            alert('Заполните все поля');
            return false;
        }
        ymaps.route([from, to]).then(
            function (route) {
                var distance = route.getLength();
                console.log(distance);
                $('input[name=distance]').val(distance);
                $('#distance-form').submit();
            },
            function (error) {
                alert('Ошибка: ' + error.message);
            }
        );
    })
});