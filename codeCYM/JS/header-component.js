
/**
 * Le header qui sera sur chaque page
 */
class headerComponent extends HTMLElement {
    constructor() {
        super()
        this.innerHTML = `
        <link rel="stylesheet" href="/yellow-checkyourmood-yellow1/codeCYM/CSS/header-component.css">
        <link rel="stylesheet" href="/yellow-checkyourmood-yellow1/codeCYM/third-party/fontawesome-free-6.2.0-web/css/all.css">
        
        <header>
            <div class="burger-menu" aria-label="Menu burger"><i class="fa-solid fa-bars"></i></div>
            <img alt="Logo CYM" src="/yellow-checkyourmood-yellow1/codeCYM/assets/images/logoCYM.png" height="70px">
            &nbsp; &nbsp;
            <form action="#" method="get" class="space">
                <input hidden name="action" value="index">
                <input hidden name="controller" value="home">
                <button type="submit" class="link check" aria-label="Logo CYM">Check Your Mood</button>
            </form>
            <form action="" method="get" class="h">
                <input hidden name="action" value="index">
                <input hidden name="controller" value="stats">
                <button type="submit" id="stats" class="link mobile" aria-label="Statistiques">Statistiques</button>
            </form>
            <form action="" method="get" class="h">
                <input hidden name="action" value="historyVal">
                <input hidden name="controller" value="stats">
                <input hidden name="page" value="1">
                <button type="submit" id="historique" class="link mobile" aria-label="Historique">Historique</button>
            </form>
            <form action="#" method="get" class="h">
                <input hidden name="action" value="index">
                <input hidden name="controller" value="humeurs">
                <button type="submit" id="humeurs" class="link mobile" aria-label="Humeurs">Humeurs</button>
            </form>
            <form action="#" method="get" class="h">
                <input hidden name="action" value="index">
                <input hidden name="controller" value="register">
                <button type="submit" class="link mobile" aria-label="Compte"><span class='fa-regular fa-user'></button>
            </form>
        </header><div class="burger"><i class="fa-solid fa-bars burger-in"></i></div>
        <nav class="nav-menu">
            <li>
                <form action="#" method="get" class="hBurger">
                    <input hidden name="action" value="index">
                    <input hidden name="controller" value="register">
                    <button type="submit" class="link Phone" aria-label="Compte">Compte</button>
                </form>
            </li>
            <li>
                <form action="#" method="get" class="hBurger">
                    <input hidden name="action" value="index">
                    <input hidden name="controller" value="humeurs">
                    <button type="submit" class="link Phone" aria-label="Humeurs">Humeurs</button>
                </form>
            </li>
            <li>
                <form action="" method="get" class="hBurger">
                    <input hidden name="action" value="index">
                    <input hidden name="controller" value="stats">
                    <input hidden name="page" value="1">
                    <button type="submit" class="link Phone" aria-label="Statistiques">Statistiques</button>
                </form>
            </li>
            <li>
                <form action="" method="get" class="hBurger">
                    <input hidden name="action" value="historyVal">
                    <input hidden name="controller" value="stats">
                    <input type = "hidden" name = "page" value = "1">
                    <button type="submit" class="link Phone" aria-label="Historique">Historique</button>
                </form>
            </li>
        </nav>`
        
    }
}

/* Crée un nouvel élément qui pourra être utilisé sur chaque page pour créer un header */
customElements.define('header-component', headerComponent)

// let stats = true;
// let humeurs = true; 
// let historique = true;

// function selectNavItem(navItem) {  
//     if (stats === true && navItem.id == "stats") {
//         stats = true;
//         humeurs = false; 
//         historique = false;
//         navItem.classList.add('link-selected');
//     }
//     if (humeurs === true && navItem.id == "humeurs") {
//         stats = false;
//         humeurs = true;
//         historique = false;
//         navItem.classList.add('link-selected');
//     }
//     if (historique === true && navItem.id == "historique") {
//         historique = true;
//         stats = false;
//         humeurs = false; 
//         navItem.classList.add('link-selected');
//     }   
// }
  
// document.querySelectorAll('.link').forEach(navItem => {
//     navItem.addEventListener('click', () => {
//         selectNavItem(navItem);
    
//     });
// });  

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