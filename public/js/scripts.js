$('button[name="play"]').on('click',function () {
    $.ajax({
        type: 'POST',
        dataType: 'JSON',
        url: '/',
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        success: function (result) {
            $('div.result').removeClass('tpl');
            $('label[name="prize"]').html(result['prize']);

            if (result['prize'] === "денежный приз"){

                $('label[for="money"]').html('Получить деньги:'+ result['sum'] +' грн.');
                $('label[for="bonusPoints"]').html('Получить бонусные баллы:'+ result['bonusPoints']);

                $('#money').val(result['sum']);
                $('#bonusPoints').val(result['bonusPoints']);

                $('div[name="money"]').removeClass('tpl');
                $('button[name="play"]').hide();

                $('button[name="confirm"]').attr('onclick','confirm("money")');


            } else if (result['prize'] === "бонусные баллы"){

                $('div[name="bonusPoints"]').removeClass('tpl');
                $('button[name="play"]').hide();

                $('label[name="bonusPoints"]').html('Вы выиграли '+result['bonusPoints']+' баллов!');
                $('label[name="bonusPoints"]').attr("data",result['bonusPoints']);

                $('button[name="confirm"]').attr('onclick','confirm("bonusPoints")');

            } else if (result['prize'] === "физический предмет"){
                $('div[name="stuff"]').removeClass('tpl');
                $('button[name="play"]').hide();

                $('label[name="stuff"]').html('Вы выиграли '+result['stuff']);
                $('label[name="stuff"]').attr("id",result['id']);
                $('label[name="stuff"]').attr("data",result['stuff']);

                $('button[name="confirm"]').attr('onclick','confirm("stuff")');
            }


        }

    });
});

function sendData(data) {
    $.ajax({
        type: 'POST',
        dataType: 'JSON',
        url: '/prize',
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        async:false,
        data: { 'data':data },
        success: function (result) {

        }
    });
}

function confirm(name) {

    //выигрыш - деньги
    if (name === "money"){
        var input = $('div[name="money"] input:checked');

        //если пользователь выбрал деньги на счет, отправляем запрос на банк для их зачисления

        /*if (input.attr('id') === "money") {
            $.ajax({
                type: 'POST',
                dataType: 'JSON',
                url: '/someURL', //тут url банка куда мы отправляем сумму для зачисления на счет
                data: {'sum': input.val()},
                success: function (result) {

                }
            });
        }*/

        // в любом случае отправляем информацию о выиграше для обновления информации в базе
        var data = {prize : input.attr('id'),sum : input.val() };

        sendData(data);

    //выигрыш - бонусные баллы
    } else if (name === "bonusPoints"){

        var input = $('label[name="bonusPoints"]');
        var data = {prize : input.attr('name'),sum : input.attr("data") };

        sendData(data);

    //выигрыш - товары
    } else if (name === "stuff"){

        var input = $('label[name="stuff"]');
        var data = {prize : input.attr('data'),id : input.attr("id") };

        sendData(data);
    }

    window.location.reload();
}