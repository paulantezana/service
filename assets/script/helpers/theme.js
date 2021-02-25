(() => {
  document.addEventListener("DOMContentLoaded", () => {
    const defaultThemes = {
      darck: {
        snColorBg: "var(--snColorDark)",
        snColorBgAlt: "var(--snColorDarker)",
        snColorHover: "var(--snColorDarkSmooth)",

        snColorText: "#b7bdc4",
        snColorTextAlt: "#94aab9",

        snColorBorder: "#343e4d",
      },
      light: {
        snColorBg: "#FAFAFA",
        snColorBgAlt: "#FFFFFF",
        snColorHover: "#0000000d",

        snColorText: "#53575A",
        snColorTextAlt: "#BABDBF",

        snColorBorder: "#E0E1E1",
      },
    };

    function buildTheme(selectTheme) {
      if (defaultThemes[selectTheme]) {
        let currentTheme = defaultThemes[selectTheme];
        let rootStyles = document.documentElement.style;
        for (const cssVarName in currentTheme) {
          if (currentTheme.hasOwnProperty(cssVarName)) {
            let property = currentTheme[cssVarName];
            rootStyles.setProperty(`--${cssVarName}`, property);
          }
        }
        sessionStorage.setItem("snTheme", selectTheme);
      }
    }

    const snTheme = sessionStorage.getItem("snTheme");
    if (snTheme) {
      buildTheme(snTheme, false);
    }

    const themeMode = document.getElementById("themeMode");
    if (themeMode) {
      themeMode.checked = snTheme === "darck";
      themeMode.addEventListener("change", () => {
        if (themeMode.checked === true) {
          buildTheme("darck");
        } else {
          buildTheme("light");
        }
      });
    }
  });
})();
