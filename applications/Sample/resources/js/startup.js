//Scope of javascript to run this application. in functions, you should use appScope
var appScope = this;

this.startSample = function(mainElement, mainFormId, publicKey) {
    appScope.topOrganizationId = 1;
    appScope.sampleApp = new appScope.SampleApp(mainElement, mainFormId, publicKey);
    appScope.sampleApp.resize(375, 200);
    appScope.sampleApp.show(true);
}

this.resizeSample = function(width, height) {
    
    appScope.a1App.resize(width, height);
}