Drupal.behaviors.blogGreeting = {
    attach(context, settings) {
      // Check if the setting exists
      if (settings.blogGreeting) {
        // Select the blog header only if it's not already modified
        document.querySelectorAll(".blog-content", context).forEach((element) => {
          if (!element.dataset.greetingApplied) {
            element.dataset.greetingApplied = true; // Prevent duplicate execution
            
            // Create and insert a greeting message
            let greeting = document.createElement("p");
            greeting.textContent = settings.blogGreeting;
            greeting.style.fontSize = "18px";
            greeting.style.color = "#007bff";
            element.prepend(greeting);
          }
        });
      }
    },
  };
  