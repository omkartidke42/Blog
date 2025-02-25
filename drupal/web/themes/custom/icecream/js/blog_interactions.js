Drupal.behaviors.blogAuthorHighlight = {
     attach(context) {
    document.querySelectorAll(".blog-post .blog-title", context).forEach((element) => {
      if (!element.dataset.highlighted) {
        element.dataset.highlighted = true; // Prevent duplicate event binding

        element.addEventListener("mouseenter", () => {
          element.style.color = "red"; // Change title color on hover
        });

        element.addEventListener("mouseleave", () => {
          element.style.color = ""; // Reset to default
        });
      }
    });
  },
  };
  