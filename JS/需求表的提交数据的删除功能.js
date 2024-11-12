document.addEventListener("DOMContentLoaded", function () {
  // 选择所有删除按钮
  var deleteButtons = document.querySelectorAll(".delete-record");

  deleteButtons.forEach(function (button) {
    button.addEventListener("click", function (event) {
      event.preventDefault(); // 阻止默认的链接行为

      if (confirm("确定要删除这条记录吗？")) {
        var recordId = this.getAttribute("data-id"); // 获取记录的ID

        // 使用 Fetch API 发起 POST 请求
        fetch(ajaxurl, {
          method: "POST",
          headers: {
            "Content-Type": "application/x-www-form-urlencoded",
          },
          body: new URLSearchParams({
            action: "delete_custom_form_data",
            record_id: recordId,
          }),
        })
          .then(function (response) {
            return response.json();
          })
          .then(function (data) {
            if (data.success) {
              alert("记录已成功删除");
              window.location.reload(); // 刷新页面以反映删除后的状态
            } else {
              alert("删除记录时出错: " + data.data);
            }
          })
          .catch(function (error) {
            alert("请求失败，请重试");
            console.error("错误:", error);
          });
      }
    });
  });
});
