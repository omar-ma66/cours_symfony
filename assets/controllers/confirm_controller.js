import { Controller } from "@hotwired/stimulus";

export default class extends Controller {
  static values = {
    message: {
      type: String,
      default: "Es-tu sûr de vouloir effectuer cette action ?",
    },
  };

  confirm(event) {
    if (!window.confirm(this.messageValue)) {
      event.preventDefault();
      event.stopPropagation();
    }
  }
}