/**
 * Le header qui sera sur chaque page
 */
class headerComponent extends HTMLElement {
    constructor() {
        super()
        this.innerHTML = `
        <link rel="stylesheet" href="/CheckYourMood/codeCYM/CSS/header-component.css">
        <link rel="stylesheet" href="/CheckYourMood/codeCYM/third-party/fontawesome-free-6.2.0-web/css/all.css">
        
        <header>
            <div class="burger-menu"><i class="fa-solid fa-bars"></i></div>
            <img src="/CheckYourMood/codeCYM/assets/images/logoCYM.png" height="70px">
            &nbsp; &nbsp;
            <form action="#" method="get" class="space">
                <input hidden name="action" value="index">
                <input hidden name="controller" value="home">
                <button type="submit" class="link check">Check Your Mood</button>
            </form>
            <form action="" method="get" class="h">
                <input hidden name="action" value="index">
                <input hidden name="controller" value="stats">
                <button type="submit" class="link mobile">Statistiques</button>
            </form>
            <form action="" method="get" class="h">
                <input hidden name="action" value="historyVal">
                <input hidden name="controller" value="stats">
                <input hidden name="page" value="1">
                <button type="submit" class="link mobile">Historique</button>
            </form>
            <form action="#" method="get" class="h">
                <input hidden name="action" value="index">
                <input hidden name="controller" value="humeurs">
                <button type="submit" class="link mobile">Humeurs</button>
            </form>
            <form action="#" method="get" class="h">
                <input hidden name="action" value="index">
                <input hidden name="controller" value="register">
                <button type="submit" class="link mobile"><span class='fa-regular fa-user'></button>
            </form>
        </header><div class="burger"><i class="fa-solid fa-bars burger-in"></i></div>
        <nav class="nav-menu">
            <li>
                <form action="#" method="get" class="hBurger">
                    <input hidden name="action" value="index">
                    <input hidden name="controller" value="register">
                    <button type="submit" class="link Phone">Compte</button>
                </form>
            </li>
            <li>
                <form action="#" method="get" class="hBurger">
                    <input hidden name="action" value="index">
                    <input hidden name="controller" value="humeurs">
                    <button type="submit" class="link Phone">Humeurs</button>
                </form>
            </li>
            <li>
                <form action="" method="get" class="hBurger">
                    <input hidden name="action" value="index">
                    <input hidden name="controller" value="stats">
                    <input hidden name="page" value="1">
                    <button type="submit" class="link Phone">Statistiques</button>
                </form>
            </li>
            <li>
                <form action="" method="get" class="hBurger">
                    <input hidden name="action" value="historyVal">
                    <input hidden name="controller" value="stats">
                    <input type = "hidden" name = "page" value = "1">
                    <button type="submit" class="link Phone">Historique</button>
                </form>
            </li>
        </nav>`
        
    }
}

/* Crée un nouvel élément qui pourra être utilisé sur chaque page pour créer un header */
customElements.define('header-component', headerComponent)

/**
 * Change l'état de la classe 'show-nav' quand on clique sur le burger menu, en version mobile, pour l'afficher ou non
 */
function toggleMenu() {
    const navbar = document.querySelector('body');
    const burger = document.querySelector('.burger-menu');
    const burger_in = document.querySelector('.burger-in');
    burger.addEventListener('click', () => {
        navbar.classList.toggle('show-nav');
    })
    burger_in.addEventListener('click', () => {
        navbar.classList.toggle('show-nav');
    })
}
toggleMenu();