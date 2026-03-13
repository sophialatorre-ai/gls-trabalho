// CONTROLE DE SEÇÕES
function showSection(sectionId) {
    const sections = document.querySelectorAll("main section");
    sections.forEach(section => {
        section.classList.add("hidden");
        section.classList.remove("active");
    });

    const target = document.getElementById(sectionId);
    if (target) {
        target.classList.remove("hidden");
        target.classList.add("active");
    }

    if (sectionId === 'home') updateDashboard();
}

// PRODUTOS
const products = [
    { id: 1, name: "Biquíni Tropical", price: 129.90, category: "banho", img: "imagens/biquini.webp", brand: "Água de Coco", stock: 10, sold: 0 },
    { id: 2, name: "Maiô Verão", price: 149.90, category: "banho", img: "imagens/maio.jpg", brand: "Caju Brasil", stock: 12, sold: 0 },
    { id: 3, name: "Saída de Praia Floral", price: 120.00, category: "saidas", img: "imagens/saida.webp", brand: "Ana Hickmann", stock: 4, sold: 0 },
    { id: 4, name: "Chinelo Praia", price: 349.90, category: "calcados", img: "imagens/birkenstock.webp", brand: "Birkenstock", stock: 20, sold: 0 },
    { id: 5, name: "Óculos de Sol", price: 200.00, category: "acessorios", img: "imagens/oculos.webp", brand: "Ray-Ban", stock: 8, sold: 0 },
    { id: 6, name: "Short Estampa", price: 159.90, category: "banho", img: "imagens/shortrl.webp", brand: "Polo Ralph Lauren", stock: 15, sold: 0 }
];

const ESTOQUE_MINIMO = 5;
let cart = [];

// EXIBIR PRODUTOS
function displayProducts(filter) {
    const productGrid = document.getElementById("product-grid");
    if (!productGrid) return;
    productGrid.innerHTML = "";

    const filtered = filter === "all" ? products : products.filter(p => p.category === filter);

    filtered.forEach(product => {
        const isEsgotado = product.stock <= 0;
        const lowStockClass = (product.stock <= ESTOQUE_MINIMO && product.stock > 0) ? "style='color: orange; font-weight: bold;'" : "";

        productGrid.innerHTML += `
            <div class="product-card ${isEsgotado ? 'esgotado' : ''}">
                <div class="product-img-container">
                    <img src="${product.img}" alt="${product.name}" onerror="this.src='imagens/placeholder.jpg'">
                    ${isEsgotado ? '<div class="badge-esgotado">ESGOTADO</div>' : ''}
                </div>
                <p><small>${product.brand}</small></p>
                <h3>${product.name}</h3>
                <p class="price">R$ ${product.price.toFixed(2)}</p>
                <p ${lowStockClass}>Estoque: ${product.stock}</p>
                <button class="btn-main" onclick="addToCart(${product.id})" ${isEsgotado ? 'disabled' : ''}>
                    ${isEsgotado ? 'Sem Estoque' : 'Adicionar'}
                </button>
            </div>
        `;
    });
}

function addToCart(id) {
    const product = products.find(p => p.id === id);
    const itemNoCarrinho = cart.find(item => item.id === id);

    if (product) {
        if (itemNoCarrinho) {
            if (product.stock > itemNoCarrinho.quantidade) {
                itemNoCarrinho.quantidade++;
            } else {
                alert("Limite de estoque atingido!");
                return;
            }
        } else {
            if (product.stock > 0) {
                cart.push({ ...product, quantidade: 1 });
            } else {
                alert("Produto esgotado!");
                return;
            }
        }
        updateCart();
    }
}

function changeQuantity(id, delta) {
    const item = cart.find(item => item.id === id);
    const product = products.find(p => p.id === id);

    if (item) {
        if (delta > 0 && item.quantidade < product.stock) {
            item.quantidade++;
        } else if (delta < 0) {
            item.quantidade--;
            if (item.quantidade <= 0) {
                cart = cart.filter(i => i.id !== id);
            }
        } else if (delta > 0) {
            alert("Limite de estoque atingido!");
        }
    }
    updateCart();
}

const FRETE_PADRAO = 20.00;
const JUROS_MENSAL = 0.0199;

function toggleCartao() {
    const metodo = document.getElementById("metodo-pagamento").value;
    const divCartao = document.getElementById("detalhes-cartao");
    if (divCartao) {
        divCartao.classList.toggle("hidden", metodo !== "credito");
    }
    updateCart();
}

function updateCart() {
    const cartItems = document.getElementById("cart-items");
    if (!cartItems) return;
    cartItems.innerHTML = "";

    let subtotal = 0;

    cart.forEach(item => {
        let totalItem = item.price * item.quantidade;
        subtotal += totalItem;

        cartItems.innerHTML += `
            <div class="cart-item" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px; border-bottom: 1px solid #eee; padding: 5px;">
                <span>${item.name}</span>
                <div class="qty-controls">
                    <button onclick="changeQuantity(${item.id}, -1)" style="padding: 2px 8px;">-</button>
                    <span style="margin: 0 10px;">${item.quantidade}</span>
                    <button onclick="changeQuantity(${item.id}, 1)" style="padding: 2px 8px;">+</button>
                </div>
                <span>R$ ${totalItem.toFixed(2)}</span>
            </div>
        `;
});

document.addEventListener("DOMContentLoaded", () => {
    displayProducts("all");
});