Drupal.behaviors.blogAuthorHighlight = {
    attach(context) {
      document.querySelectorAll(".blog-post .author").forEach((element) => {
        if (!element.dataset.highlighted) {
          element.dataset.highlighted = true; // Ensures behavior only runs once
  
          element.addEventListener("mouseenter", () => {
            element.style.backgroundColor = "#ffff99";
          });
  
          element.addEventListener("mouseleave", () => {
            element.style.backgroundColor = "";
          });
        }
      });
    },
  };
  