(function ($, Drupal) {
  Drupal.behaviors.blogInteractions = {
    attach: function (context, settings) {
      $(".like-btn", context).once("blog-likes").click(async function () {
        let button = $(this);
        let nid = button.closest(".custom-meta").attr("data-nid");

        if (!nid || nid === "0") {
          console.error("❌ ERROR: Invalid Node ID", nid);
          return;
        }

        try {
          let response = await fetch(`/api/blog/${nid}/like`, { method: "POST" });
          let data = await response.json();

          if (data.status === "success") {
            button.find(".likes-count").text(data.new_likes_count);
            button.addClass("active"); // Add red color effect
          }
        } catch (error) {
          console.error("❌ ERROR updating likes:", error);
        }
      });

      $(".view-btn", context).once("blog-views").click(async function () {
        let button = $(this);
        let nid = button.closest(".custom-meta").attr("data-nid");

        if (!nid || nid === "0") {
          console.error("❌ ERROR: Invalid Node ID", nid);
          return;
        }

        try {
          let response = await fetch(`/api/blog/${nid}/view`, { method: "POST" });
          let data = await response.json();

          if (data.status === "success") {
            button.find(".views-count").text(data.new_views_count);
          }
        } catch (error) {
          console.error("❌ ERROR updating views:", error);
        }
      });
    },
  };
})(jQuery, Drupal);
