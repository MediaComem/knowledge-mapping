import noUiSlider from "./nouislider.min.js";
jQuery(document).ready(function ($) {

    $("#js-menu-button").on("click", function () {
        $(this).toggleClass("open");
        $("#js-main-nav").toggleClass("open");
        $("#back-menu-overlay").toggleClass("open");
    });

    $("#back-menu-overlay").on("click", function () {
        $(this).toggleClass("open");
        $("#js-main-nav").toggleClass("open");
        $("#js-menu-button").toggleClass("open");
    });

    $(".page-article__back, .menu-button__back").on("click", function () {
        window.history.back();
    });

    $(".main-nav a").on("click", function (e) {
        let href = $(this).attr("href");
        e.preventDefault();
        $("#back-menu-overlay").removeClass("open");
        $("#js-main-nav").removeClass("open");
        $("#js-menu-button").removeClass("open");
        window.location = href;
    });

    $(".page-search__input").on("input", function () {
        let $val = $(this).val();
        if ($val.length > 2) {
            $(".page-search__clear").fadeIn();
        }
    });

    $("#js-clear").click(function () {
        $(".page-search__input").val("");
        $(".page-search__clear").fadeOut();
    });

    $("#pop-close").on("click", function () {
        $(this).parent().parent().parent().fadeOut();
    });

    $(".page-article__comment").click(function () {
        $("html, body").animate(
            {
                scrollTop: $("#commentary").offset().top,
            },
            2000
        );
        return false;
    });

    $(".page-article__share").click(function (e) {
        e.preventDefault();
        $(".share-list").toggleClass("share-list--open");
    });
});

const slider = document.getElementById("slider-date");
if (slider) {
    let inputNumber1 = document.getElementById("inputDate1");
    let inputNumber2 = document.getElementById("inputDate2");

    let inputs = [inputNumber1, inputNumber2];

    let in1 = parseInt($("#inputDate1").val());
    let in2 = parseInt($("#inputDate2").val());
    let max = parseInt($("#slider-date").data("max"));
    let min = parseInt($("#slider-date").data("min"));

    noUiSlider.create(slider, {
        start: [in1, in2],
        connect: true,
        step: 1,
        range: {
            min: min,
            max: max,
        },
        format: {
            to: function (value) {
                return Math.round(value);
            },
            from: function (value) {
                return Math.round(value);
            },
        },
    });

    slider.noUiSlider.on("slide", (values, handle) => {
        inputs[handle].value = values[handle];
    });

    slider.noUiSlider.on("set", (values, handle) => {
        //needed because of the custom slider
        window.Livewire.dispatch("form-submitted", {
            dateFrom: values[0],
            dateTo: values[1],
        });
    });

    inputs.forEach((input, handle) => {
        input.addEventListener("change", () => {
            slider.noUiSlider.set([inputNumber1.value, inputNumber2.value]);
        });
    });

    document.addEventListener("livewire:init", () => {
        window.Livewire.hook("commit", ({ succeed }) => {
            succeed(() => {
                Alpine.nextTick(() => {
                    // noUISlider handles need to be updated on submit to match the new date range
                    if (
                        inputNumber1.value == min &&
                        inputNumber2.value == max
                    ) {
                        slider.noUiSlider.set([min, max], false, false);
                    }
                    // highlight search terms in results
                    let queryParams = document.querySelector(
                        ".page-search__input"
                    ).value;
                    if (!queryParams.length) {
                        return;
                    }

                    const queryParamsArray = queryParams
                        .toLowerCase()
                        .split(" ")
                        .map((queryParam) =>
                            queryParam.trim().replace('"', "")
                        );
                    const queryResultsEls = document.querySelectorAll(
                        ".search-results-item"
                    );
                    queryParamsArray.forEach((queryParam) => {
                        // gi = global, case insensitive
                        const regex = new RegExp(queryParam, "gi");
                        queryResultsEls.forEach((el) => {
                            if (!el.innerHTML.includes("<mark>")) {
                                el.innerHTML = el.innerHTML.replaceAll(
                                    regex,
                                    (matched) => {
                                        return "<mark>" + matched + "</mark>";
                                    }
                                );
                            }
                        });
                    });
                });
            });
        });
    });
}
