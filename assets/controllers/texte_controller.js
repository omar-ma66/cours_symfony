import { Controller } from '@hotwired/stimulus';
export default class extends Controller {
static targets = ["test1","test2","test3"]
static values ={
    "message1":String,
    "message2":String,
    "message3":String
}
    connect() {
        // this.element.textContent = 'Hello Stimulus! Edit me in assets/controllers/hello_controller.js';
        console.log("Le controleur texte fonctionne bien");
    }

   func1()
   {
     if (this.test1Target.textContent !== this.message1Value)
      this.test1Target.textContent = this.message1Value;
   }
   out1()
   {
    this.test1Target.textContent = "plus de survole"
   }


   func2()
   {
     if (this.test2Target.textContent !== this.message2Value)
      this.test2Target.textContent = this.message2Value;

   }
 out2()
   {
    this.test2Target.textContent = "plus de survole"
   }


   func3()
   {
     if (this.test3Target.textContent !== this.message3Value)
      this.test3Target.textContent = this.message3Value;

   }
    out3()
   {
    this.test3Target.textContent = "plus de survole"
   }
}
