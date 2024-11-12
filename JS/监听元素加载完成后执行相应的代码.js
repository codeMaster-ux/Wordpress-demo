// 创建一个MutationObserver实例
const observer = new MutationObserver((mutationsList, observer) => {
  for (let mutation of mutationsList) {
    if (mutation.type === "childList") {
      const targetElement = document.querySelector("#wsf-1-field-72");
      if (targetElement) {
        // 元素已经加载，执行你的代码
        observer.disconnect(); // 停止观察
        // 在这里执行你的代码
        executeYourCode();
      }
    }
  }
});

// 观察整个文档的子节点变化
observer.observe(document, { childList: true, subtree: true });
function executeYourCode() {
  // 获取localStorage中的pageTitle值
  var pageTitle = localStorage.getItem("pageTitle");

  // 获取要隐藏的元素
  var inputWrapper = document.getElementById("wsf-1-field-72");
  // 检查pageTitle是否有值
  if (!pageTitle) {
    // 如果没有值，隐藏inputWrapper
    inputWrapper.style.display = "none";
  }
}
