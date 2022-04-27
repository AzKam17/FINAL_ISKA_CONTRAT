const $ = require('jquery');
const angular = require("angular");
global.$ = global.jQuery = $;

let $collectionHolder;
let addPreuveButton = $('<button type="button" class="add_preuve">Ajouter une preuve</button>');

$(document).ready(function (){
    $collectionHolder = $('div.preuves_accordion');
    $collectionHolder.append(newPreuve);
    $collectionHolder.data('index', $collectionHolder.find('div.card_preuves').length);
    addPreuveButton.on('click', (e) => {
        addPreuveForm($collectionHolder, newPreuve);
    });
    let newPreuve = $('#posBtn').append(addPreuveButton);

    $('.add_action_btn').on('click', ajouterAction);
})

const addPreuveForm = ($collectionHolder, newPreuve) => {
    const proto = '<div id="obligation_preuves__azk__actions___name__"><div><label for="obligation_preuves__azk__actions___name___action" class="required">Action</label><textarea id="obligation_preuves__azk__actions___name___action" name="obligation[preuves][1][actions][__name__][action]" required="required" class="form-control customAction"></textarea></div><div><label for="obligation_preuves__azk__actions___name___resultatAttendu" class="required">Résultat Attendu</label><textarea id="obligation_preuves__azk__actions___name___resultatAttendu" name="obligation[preuves][1][actions][__name__][resultatAttendu]" required="required" class="form-control"></textarea></div><div><label for="obligation_preuves__azk__actions___name___porteur" class="required">Porteur</label><input type="text" id="obligation_preuves__azk__actions___name___porteur" name="obligation[preuves][1][actions][__name__][porteur]" required="required" class="form-control customAction" /></div><div><label for="obligation_preuves__azk__actions___name___statutAction" class="required">Délai de dénonciation</label><select id="obligation_preuves__azk__actions___name___statutAction" name="obligation[preuves][1][actions][__name__][statutAction]" class="form-control customAction"><option value="En cours">En cours</option><option value="Terminé">Terminé</option></select></div><div><label class="required">Délai</label><div id="obligation_preuves__azk__actions___name___delai" class="form-control customAction"><select id="obligation_preuves__azk__actions___name___delai_month" name="obligation[preuves][1][actions][__name__][delai][month]"><option value="1">Jan</option><option value="2">Feb</option><option value="3">Mar</option><option value="4">Apr</option><option value="5">May</option><option value="6">Jun</option><option value="7">Jul</option><option value="8">Aug</option><option value="9">Sep</option><option value="10">Oct</option><option value="11">Nov</option><option value="12">Dec</option></select><select id="obligation_preuves__azk__actions___name___delai_day" name="obligation[preuves][1][actions][__name__][delai][day]"><option value="1">1</option><option value="2">2</option><option value="3">3</option><option value="4">4</option><option value="5">5</option><option value="6">6</option><option value="7">7</option><option value="8">8</option><option value="9">9</option><option value="10">10</option><option value="11">11</option><option value="12">12</option><option value="13">13</option><option value="14">14</option><option value="15">15</option><option value="16">16</option><option value="17">17</option><option value="18">18</option><option value="19">19</option><option value="20">20</option><option value="21">21</option><option value="22">22</option><option value="23">23</option><option value="24">24</option><option value="25">25</option><option value="26">26</option><option value="27">27</option><option value="28">28</option><option value="29">29</option><option value="30">30</option><option value="31">31</option></select><select id="obligation_preuves__azk__actions___name___delai_year" name="obligation[preuves][1][actions][__name__][delai][year]"><option value="2017">2017</option><option value="2018">2018</option><option value="2019">2019</option><option value="2020">2020</option><option value="2021">2021</option><option value="2022">2022</option><option value="2023">2023</option><option value="2024">2024</option><option value="2025">2025</option><option value="2026">2026</option><option value="2027">2027</option></select></div></div></div>';
    let prototype = $collectionHolder.data('prototype');
    const index = $collectionHolder.data('index');

    let newForm = prototype;
    newForm = newForm.replace(/__name__/g, index);
    let newProto = proto;
    newProto = newProto.replace(/__azk__/g, index);
    $collectionHolder.data('index', index + 1);


    let labels = $(newForm).find('div label');
    let inputs = $(newForm).find('input');

    $templatePreuve = $('<div></div>').addClass('card card-primary card_preuves').append(
        $('<div></div>').addClass('card-header').append(
            $('<h4></h4>').addClass('card-title w-100').append(
                $('<a></a>')
                    .addClass('d-block w-100')
                    .attr('data-toggle', 'collapse')
                    .attr('href', `#collapse_preuves_${index + 1}`)
                    .append(`Preuve #${index + 1}`)
            )
        )
    ).append(
        $('<div></div>')
            .attr('id', `collapse_preuves_${index + 1}`)
            .addClass('collapse')
            .attr('data-parent', '#accordion')
            .append(
                $('<div></div>')
                    .addClass('card-body')
                    .append(
                        $('<div></div>')
                            .addClass('row mt-1')
                            .append(
                                $('<div></div>')
                                    .addClass('col')
                                    .append(
                                        $('<div></div>')
                                            .addClass('form-group')
                                            .append( labels[0] )
                                            .append( inputs[0] )
                                    )
                            )
                    )
                    .append(
                        $('<div></div>')
                            .addClass('row mt-1')
                            .append(
                                $('<div></div>')
                                    .addClass('col')
                                    .append(
                                        $('<div></div>')
                                            .addClass('form-group')
                                            .append( labels[1] )
                                            .append( inputs[1] )
                                    )
                            )
                    )
                    .append(
                        $('<div></div>')
                            .addClass('card')
                            .append(
                                $('<div></div>')
                                    .addClass('card-header')
                                    .append(
                                        $('<div></div>')
                                            .addClass('card-title')
                                            .append('Actions')
                                    )
                            ).append(
                            $('<div></div>')
                                .addClass('card-body')
                                .append(
                                    $('<div></div>')
                                        .attr('id', `accordion_${index + 1}`)
                                        .attr('data-preuve', index + 1)
                                        .attr('data-prototype', newProto)
                                )
                                .append(
                                    $('<button></button>')
                                        .attr('type', 'button')
                                        .addClass('add_action_btn')
                                        .append('Ajouter Action')
                                        .on('click', ajouterAction)
                                )
                        )
                    )
            )
    )

    $('#accordion').append($templatePreuve);
}

const ajouterAction = function (e){
    let parent = $(this).siblings()[0];
    parent = $(parent);
    let prototype = parent.data('prototype');
    const index = (parent.children()).length + 1;
    const preuveIndex = parent.data('preuve');


    let labels = $(prototype).find('div label');
    let formsGroup = $(prototype).find('.customAction');

    $templatePreuve = $('<div></div>')
        .addClass('card card-default')
        .append(
            $('<div></div>')
                .addClass('card-header')
                .append(
                    $('<h4></h4>')
                        .addClass("card-title w-100")
                        .append(
                            $('<a></a>')
                                .addClass('d-block w-100')
                                .attr('href', `#collapseOne_${ preuveIndex }_${index}_id`)
                                .attr('data-toggle', 'collapse')
                                .append(`Preuve ${preuveIndex} - Action #${index}`)
                        )
                )
        ).append(
            $('<div></div>')
                .addClass('collapse')
                .attr('id', `collapseOne_${preuveIndex}_${index}_id`)
                .attr('data-parent', `#accordion_${preuveIndex}`)
                .append(
                    $('<div></div>')
                        .addClass('card-body')
                        .append(
                            $('<div></div>')
                                .addClass('row mt-1')
                                .append(
                                    $('<div></div>')
                                        .addClass('col')
                                        .append(
                                            $('<div></div>')
                                                .addClass('form-group')
                                                .append( labels[0] )
                                                .append( formsGroup[0] )
                                        )
                                )
                        )
                        .append(
                            $('<div></div>')
                                .addClass('row mt-1')
                                .append(
                                    $('<div></div>')
                                        .addClass('col')
                                        .append(
                                            $('<div></div>')
                                                .addClass('form-group')
                                                .append( labels[2] )
                                                .append( formsGroup[1] )
                                        )
                                )
                        )
                        .append(
                            $('<div></div>')
                                .addClass('row mt-1')
                                .append(
                                    $('<div></div>')
                                        .addClass('col')
                                        .append(
                                            $('<div></div>')
                                                .addClass('form-group')
                                                .append( labels[3] )
                                                .append( formsGroup[2] )
                                        )
                                )
                        )
                        .append(
                            $('<div></div>')
                                .addClass('row mt-1')
                                .append(
                                    $('<div></div>')
                                        .addClass('col')
                                        .append(
                                            $('<div></div>')
                                                .addClass('form-group')
                                                .append( labels[4] )
                                                .append( formsGroup[3] )
                                        )
                                )
                        )
                )
        );

    $(`#accordion_${preuveIndex}`).append($templatePreuve);
}
