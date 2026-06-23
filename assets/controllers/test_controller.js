import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
static targets = ["modiffier"]

static values = {
    texte:String
}
    connect() {
        // this.element.textContent = 'Hello Stimulus! Edit me in assets/controllers/hello_controller.js';
        console.log("Le controleur test  est bien connecté !");
    }

    change()
    {
        
        this.modiffierTarget.textContent = this.texteValue ;
    }
}
