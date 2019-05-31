var StepWizard = function () {

    return {

        initStepWizard: function () {
            var form = $(".shopping-cart");
                form.children("div").steps({
                    headerTag: ".header-tags",
                    bodyTag: "section",
                    transitionEffect: "fade",
                    onStepChanging: function (event, currentIndex, newIndex) {
                    	 if (StepMakeOrder && newIndex==0){                         	 goToCartStep(StepMakeOrder);
                    	 }

                        // newIndex
                        // Allways allow previous action even if the current form is not valid!

                        if (currentIndex > newIndex)
                        {
                            return true;
                        }
                        form.validate().settings.ignore = ":disabled,:hidden";
                        return form.valid();
                    },
                    onFinishing: function (event, currentIndex) {
                        form.validate().settings.ignore = ":disabled";
                        return form.valid();
                    },
                    onFinished: function (event, currentIndex) {
                        submitForm('Y');
                    }
                });
        },

    };
}();