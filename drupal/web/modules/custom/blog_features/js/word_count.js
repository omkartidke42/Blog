Drupal.behaviors.wordCount = {
    attach(context, settings) {
        console.log("Word count script loaded!");

      let textarea = document.querySelector("textarea[name='body[0][value]']");
      if (!textarea) return;
  
      let wordCountDiv = document.getElementById("word-count-info");
      if (!wordCountDiv) {
        wordCountDiv = document.createElement("div");
        wordCountDiv.id = "word-count-info";
        textarea.parentNode.appendChild(wordCountDiv);
      }
  
      function updateWordCount() {
        let text = textarea.value.trim();
        let wordCount = text ? text.split(/\s+/).length : 0;
        let readTime = Math.ceil(wordCount / 200);
        wordCountDiv.innerHTML = `Word Count: ${wordCount}, Estimated Reading Time: ${readTime} min`;
      }
  
      textarea.addEventListener("input", updateWordCount);
      updateWordCount();
    }
  };
  