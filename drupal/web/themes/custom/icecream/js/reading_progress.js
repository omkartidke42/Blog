Drupal.behaviors.readingProgress = {
    attach(context, settings) {
      // Ensure script runs only once
      if (!context.querySelector("#reading-progress-bar")) {
        // Create the progress bar element
        let progressBar = document.createElement("div");
        progressBar.id = "reading-progress-bar";
        progressBar.style.position = "fixed";
        progressBar.style.top = "0";
        progressBar.style.left = "0";
        progressBar.style.width = "0%";
        progressBar.style.height = "5px";
        progressBar.style.backgroundColor = "#007bff";
        progressBar.style.zIndex = "9999";
        document.body.prepend(progressBar);
  
        // Update progress bar on scroll
        window.addEventListener("scroll", () => {
          let scrollTop = document.documentElement.scrollTop;
          let scrollHeight = document.documentElement.scrollHeight - window.innerHeight;
          let progress = (scrollTop / scrollHeight) * 100;
          progressBar.style.width = progress + "%";
        });
      }
    },
  };
  