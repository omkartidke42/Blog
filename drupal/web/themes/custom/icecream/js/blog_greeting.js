(function (Drupal) {
  Drupal.behaviors.blogGreeting = {
    attach: function (context, settings) {
      if (settings.blogGreeting) {
        document.querySelectorAll(".blog-content", context).forEach((element) => {
          if (!element.dataset.greetingApplied) {
            element.dataset.greetingApplied = "true"; 

            let greeting = document.createElement("p");
            greeting.textContent = settings.blogGreeting;
            greeting.style.fontSize = "18px";
            greeting.style.color = "#007bff";
            element.prepend(greeting);
          }
        });
      }
    }
  };
})(Drupal);
