this.SampleApp = Class.create();

var appScope = this;

this.SampleApp.prototype = {

    show: function(isShow) {
        if (isShow) {
            this.mainElement.show();
        }
        else {
            this.mainElement.hide();
        }
    },

    resize: function (width, height) {

    },

    initialize: function(mainElement, mainFormId, publicKey) {
        this.mainElement = $(mainElement);
        this.mainFormId = mainFormId;
        this.publicKey = publicKey;

        var hanaWindow = window.hanaDesktop.getWindow(mainFormId);
        hanaWindow.getContentContainer().setStyle({ position: 'absolute', top: '0px', left: '0px', right: '0px', bottom: '0px' });
    }
}
