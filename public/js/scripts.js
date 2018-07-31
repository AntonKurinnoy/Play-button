$('button[name="play"]').on('click',function () {
    $.ajax({
        type: 'POST',
        dataType: 'JSON',
        url: '/',
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        success: function (result) {
            $('div.result').removeClass('tpl');
            $('label[name="prize"]').html(result['prize']);

            /*for (var i in result)
                console.log(result[i]);*/

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

                $('label[name="bonusPoints"]').html('Вы вытграли '+result['bonusPoints']+' баллов!');
                $('label[name="bonusPoints"]').attr("data",result['bonusPoints']);

                $('button[name="confirm"]').attr('onclick','confirm("bonusPoints")');

            } else if (result['prize'] === "физический предмет"){

            }


        }

    });
});

function confirm(name) {

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

    } else if (name === "bonusPoints"){

        var input = $('label[name="bonusPoints"]');
        var data = {prize : input.attr('name'),sum : input.attr("data") };

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

    window.location.reload();
}