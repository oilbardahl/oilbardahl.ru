var MouseWheel = function () {

    return {

        initMouseWheel: function () {
            jQuery('.slider-snap').noUiSlider({
                start: [ 1000, 4500 ],
                snap: true,
                connect: true,
                range: {
                    'min': 0,
                    '5%': 500,
                    '10%': 1000,
                    '15%': 1500,
                    '20%': 2000,
                    '25%': 2500,
                    '30%': 3000,
                    '35%': 3500,
                    '40%': 4000,
                    '50%': 4500,
                    '60%': 5000,
                    '70%': 5500,
                    '80%': 6000,
                    '90%': 6500,
                    'max': 7000
                }
            });
            jQuery('.slider-snap').Link('lower').to(jQuery('.slider-snap-value-lower'));
            jQuery('.slider-snap').Link('upper').to(jQuery('.slider-snap-value-upper'));
        }

    };

}();