<script setup>
import { Head, Link } from '@inertiajs/vue3';
import { onMounted } from "vue";

const scrollToSection = (event, sectionId) => {
  event.preventDefault(); // Evita o comportamento padrão do link

  const section = document.getElementById(sectionId);
  if (section) {
    section.scrollIntoView({ behavior: "smooth", block: "start" });
  }
};

defineProps({
    estampadoras: {
        type: Array,
    },
    produtos: {
        type: Array,
    },
});

function handleImageError() {
    document.getElementById('screenshot-container')?.classList.add('!hidden');
    document.getElementById('docs-card')?.classList.add('!row-span-1');
    document.getElementById('docs-card-content')?.classList.add('!flex-row');
    document.getElementById('background')?.classList.add('!hidden');
}
</script>

<template>
    <div class="bg-white text-gray-900">
      <!-- Navbar no 12 -->
      <nav class="bg-white shadow-md fixed w-full top-0 z-50">
        <div class="container mx-auto flex justify-between items-center py-4 px-6">
            <div class="logo-container flex justify-center items-center">
              <img src="../../../public/images/logo.jpg" alt="Logo da Empresa" class="w-20 h-auto scale-150" />
          </div>
        <ul class="flex space-x-6 text-lg">
          <li><a href="#home" @click="scrollToSection($event, 'home')" class="hover:text-red-600">Início</a></li>
          <li><a href="#sobre" @click="scrollToSection($event, 'sobre')" class="hover:text-red-600">Quem Somos</a></li>
          <li><a href="#produtos" @click="scrollToSection($event, 'produtos')" class="hover:text-red-600">Produtos</a></li>
          <li><a href="#lojas" @click="scrollToSection($event, 'lojas')" class="hover:text-red-600">Nossas Lojas</a></li>
          <li><a href="#duvidas" @click="scrollToSection($event, 'duvidas')" class="hover:text-red-600">Dúvidas Frequentes</a></li>
          <!-- <li><a href="#contato" @click="scrollToSection($event, 'contato')" class="hover:text-red-600">Contato</a></li> -->
        </ul>
          <a href="/login" class="bg-red-600 text-white py-2 px-4 rounded-lg hover:bg-red-700">Área Restrita</a>
        </div>
      </nav>
  
      <!-- Inicial -->
      <section id="home" class="h-screen flex flex-col justify-center items-center text-center bg-gray-100 px-4 fade-in">
        <h1 class="text-5xl font-bold">Bem-vindo à Nossa Empresa</h1>
        <div> <img src="../../../public/images/suv.gif" alt=""></div>
        <p class="text-lg mt-4">Soluções inovadoras para o seu negócio.</p>
        <a href="https://wa.me/5555988420909" class="mt-6 bg-red-600 text-white py-2 px-6 rounded-lg hover:bg-red-700">Entre em Contato</a>
      </section>
      
      <!-- Sobre -->
      <section id="sobre" class="py-20 px-6 text-center fade-in bg-white-100">
  <h2 class="text-4xl font-semibold mb-4">Sobre Nós</h2>
  
  <div class="flex flex-col md:flex-row items-center justify-center gap-8">
    <div class="w-full md:w-1/2 flex justify-center">
      <img src="../../../public/images/foto.png" alt="Imagem" class="w-3/4 max-w-md h-auto rounded-lg shadow-lg object-contain">
    </div>
    
    <div class="w-full md:w-1/2 p-8 text-center md:text-left">
      <h2 class="text-2xl font-bold text-gray-800 text-center">A Nova Era Placas</h2>
      <p class="mt-4 text-lg text-gray-600">
        É sinônimo de qualidade, compromisso e transparência. Nossa equipe altamente capacitada garante um atendimento ágil, personalizado e focado na satisfação do cliente. Trabalhamos com responsabilidade para entregar soluções seguras e de alta qualidade. Escolha a excelência. Escolha a Nova Era Placas!
      </p>
      <p class="mt-4 text-lg text-gray-600">
       Nossos Blanks são feitos com matéria prima com alto padrão e possui O selo ISO 9001 que é um certificado de qualidade que atesta que uma empresa segue os requisitos de uma norma internacional para gestão de qualidade. 
      </p>
      <p class="mt-4 text-lg text-gray-600">
        A ISO 7591 é uma norma internacional que especifica as características de placas de matrícula de veículos automotores e reboques. A norma foi publicada em 1982 e revisada e confirmada em 2022. 
      </p><br>
      <div class="flex flex-col items-center py-10 bg-gray-100">
    
        <div class="flex justify-between items-start">
  <!-- Seção de Logos e Textos -->
  <div class="flex flex-col space-y-4 text-red-600">
    <div class="flex items-center text-3xl font-semibold">
      <img src="../../../public/images/seta.gif" alt="Logo" class="h-12 w-auto mr-4">
      <span>Qualidade</span>
    </div>
    <div class="flex items-center text-3xl font-semibold">
      <img src="../../../public/images/seta.gif" alt="Logo" class="h-12 w-auto mr-4">
      <span>Confiança</span>
    </div>
    <div class="flex items-center text-3xl font-semibold">
      <img src="../../../public/images/seta.gif" alt="Logo" class="h-12 w-auto mr-4">
      <span>Credibilidade</span>
    </div>
    <div class="flex items-center text-3xl font-semibold">
      <img src="../../../public/images/seta.gif" alt="Logo" class="h-12 w-auto mr-4">
      <span>Garantia</span>
    </div>
    <div class="flex items-center text-3xl font-semibold">
      <img src="../../../public/images/seta.gif" alt="Logo" class="h-12 w-auto mr-4">
      <span>Eficiência</span>
    </div>
  </div>

  <!-- Seção de Imagens à Direita -->
  <div class="flex flex-col items-center space-y-16">
    <img src="../../../public/images/selo.png" alt="Imagem 1" class="h-48 w-48 object-contain">
</div>
<div class="flex flex-col items-center space-y-16">
    <img src="../../../public/images/iso.png" alt="Imagem 2" class="h-48 w-48 object-contain">
</div>
</div>

</div>
    </div>
  </div>
        
      </section>
  
      <!-- Produtos -->
      <section v-if="produtos.length > 0" id="produtos" class="py-20 bg-gray-100 px-6 text-center fade-in">
        <div class="container mx-auto px-6">
        <h2 class="text-4xl font-bold text-center text-gray-800 mb-12">Nossos Produtos</h2>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
          <div 
            v-for="(produto, index) in produtos" 
            :key="index" 
            class="bg-white rounded-lg shadow-md overflow-hidden transform transition-all duration-300 hover:scale-110 hover:shadow-2xl"
          >
            <img :src="produto.imagem" :alt="produto.nome" class="w-full">
            <div class="p-4 text-left">
              <h2 class="font-semibold text-gray-800" style="font-size: 25px;">{{ produto.nome }}</h2>
              <p class="text-gray-600 mt-2">{{ produto.descricao }}</p>
              <p class="text-lg font-bold text-blue-600 mt-2">{{ produto.valor }}</p>
            </div>
          </div>
        </div>
      </div>
      </section>
  
      <!-- Lojas -->
      <div class="bg-white text-gray-900">
    <section id="lojas" class="py-20 px-6 text-center fade-in">
      <h2 class="text-3xl font-semibold">Onde Estamos</h2>
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mt-8">
        
        <div v-for="(loja, index) in estampadoras" :key="index" 
             class="relative bg-white p-4 rounded-lg shadow-lg overflow-hidden hover:scale-105 hover:shadow-2xl transition-all duration-300 cursor-pointer" 
             @click="toggleCard(index)">
          <img :src="loja.imagem" alt="Loja" class="w-full h-auto object-contain rounded-t-lg">
          <div class="p-4" :class="{'h-32': !loja.expandido, 'h-auto': loja.expandido}">
            <h3 class="text-xl font-semibold text-red-600">{{ loja.nome }}</h3>
            <p class="text-sm text-gray-700 mt-2 dark:text-gray-400">CNPJ: {{ loja.cnpj }}</p>
            <p class="text-sm text-gray-700 dark:text-gray-400">Telefone: {{ loja.telefone }}</p>
            <p class="text-sm text-gray-700 dark:text-gray-400">Localização: 
              <a :href="'https://www.google.com/maps?q=' + loja.endereco.latitude + ',' + loja.endereco.longitude" target="_blank" class="text-red-600 underline">Ver no Maps</a>
            </p>
          </div>
        </div>
        
      </div>
    </section>
  </div>
  
     
    <!-- Cards de Dúvidas Frequentes -->
    <section id="duvidas" class="py-20 px-6 bg-gray-100">
    <h2 class="text-3xl font-semibold text-center mb-8">Dúvidas Frequentes</h2>
    <div class="flex flex-wrap justify-center gap-6">
      <div v-for="(faq, index) in faqs" :key="index" class="w-full sm:w-1/2 lg:w-1/3">
        <div
          class="bg-white shadow-lg rounded-lg p-6 mb-4 cursor-pointer hover:scale-105 hover:shadow-2xl"
          @click="openModal(faq)"
        >
          <h3 class="text-xl font-semibold text-gray-800">{{ faq.question }}</h3>
        </div>
      </div>
    </div>

    <!-- Modal (janela de FAQ expandido) -->
    <transition name="modal">
      <div v-if="selectedFaq" class="fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center z-50">
        <div class="bg-white p-8 rounded-lg shadow-lg w-[600px]">
          <h3 class="text-2xl font-semibold text-gray-800 mb-4">{{ selectedFaq.question }}</h3>
          <p class="text-lg text-gray-700 mb-4">{{ selectedFaq.answer }}</p>
          <button
            @click="closeModal"
            class="mt-4 px-4 py-2 bg-red-600 text-white font-semibold rounded-lg hover:bg-red-700"
          >
            Fechar
          </button>
        </div>
      </div>
    </transition>
  </section>
  
      <!-- Contato -->
      <!-- <section id="contato" class="py-20 bg-white-100 px-6 text-center fade-in">
        <h2 class="text-3xl font-semibold">Entre em Contato</h2>
        <form class="mt-6 max-w-lg mx-auto">
          <input type="text" placeholder="Seu Nome" class="w-full p-3 border rounded-lg mb-4" />
          <input type="email" placeholder="Seu Email" class="w-full p-3 border rounded-lg mb-4" />
          <textarea placeholder="Sua Mensagem" class="w-full p-3 border rounded-lg mb-4"></textarea>
          <button type="submit" class="bg-red-600 text-white py-2 px-6 rounded-lg hover:bg-red-700">Enviar</button>
        </form>
      </section> -->
  
      <!-- Rodapé -->
      <footer class="bg-red-600 text-white py-6">
    <div class="container mx-auto flex flex-col md:flex-row justify-between items-center px-6">
      <!-- Direitos Autorais -->
      <p class="text-sm text-center md:text-left">
        © {{ new Date().getFullYear() }} Nova Era Placas - Todos os direitos reservados.
      </p>

      <!-- Redes Sociais -->
      <div class="flex space-x-4 mt-4 md:mt-0">
        <a href="https://www.instagram.com/novaera.placass/" target="_blank" class="hover:text-gray-300">
          <i class="fab fa-instagram text-2xl"></i>
        </a>
        <a href="https://www.facebook.com/people/Nova-Era-Placas/61559921292385/" target="_blank" class="hover:text-gray-300">
          <i class="fab fa-facebook text-2xl"></i>
        </a>
        <a href="https://wa.me/559884209099" target="_blank" class="hover:text-gray-300">
          <i class="fab fa-whatsapp text-2xl"></i>
        </a>
        <a href="mailto:novaeraplacas@yahoo.com" class="hover:text-gray-300">
          <i class="fas fa-envelope text-2xl"></i>
        </a>
      </div>
    </div>
  </footer>
    </div>
  </template>
  
  <script>
  export default {
  data() {
    return {
      lojas: [
        { nome: "ACAILÂNDIA", cnpj:"50.483.492/0013-25", telefone: "(00) 0000-0001", imagem: "/images/ACAILANDIA.png", maps: "https://maps.app.goo.gl/EzL2ggDGavA3mQPi6", expandido: false },
        { nome: "BACABAL", cnpj:"50.483.492/0009-49", telefone: "(00) 0000-0002", imagem: "/images/BACABAL.png", maps: "https://maps.app.goo.gl/m2ZHh3ccYwVnBiUX9", expandido: false },
        { nome: "BALSAS", cnpj:"50.483.492/0002-72", telefone: "(99) 98154-8049", imagem: "/images/BALSAS.png", maps: "https://maps.app.goo.gl/SsGgcjiozWYYoy8P7", expandido: false },
        { nome: "BARRA DO CORDA", cnpj:"50.483.492/0006-04", telefone: "(00) 0000-0004", imagem: "/images/BARRADOCORDA.png", maps: "https://maps.app.goo.gl/k5jwtG8JdYC3mLMj9", expandido: false },
        { nome: "CAXIAS", cnpj:"50.483.492/0003-53", telefone: "(00) 0000-0005", imagem: "/images/CAXIAS.png", maps: "https://maps.app.goo.gl/MAw3YtHT3Qn9mRqu5", expandido: false },
        { nome: "CHAPADINHA", cnpj:"50.483.492/0014-06", telefone: "(00) 0000-0006", imagem: "/images/CHAPADINHA.png", maps: "https://maps.app.goo.gl/bVQtD85wmCegQxGY8", expandido: false },
        { nome: "CODÓ", cnpj:"50.483.492/0010-82", telefone: "(00) 0000-0007", imagem: "/images/CODO.png", maps: "https://maps.app.goo.gl/oo27TK1oWTgXWqXH6", expandido: false },
        { nome: "GRAJAÚ", cnpj:"50.483.492/0015-97", telefone: "(00) 0000-0008", imagem: "/images/GRAJAU.png", maps: "https://maps.app.goo.gl/dRssdMnLZ7JjEsrJ9", expandido: false },
        { nome: "IMPERATRIZ", cnpj:"50.483.492/0016-78", telefone: "(00) 0000-0001", imagem: "/images/IMPERATRIZ.png", maps: "https://maps.app.goo.gl/xmGvnpNUrR1yhovn6", expandido: false },
        { nome: "PEDREIRAS", cnpj:"50.483.492/0008-68", telefone: "(00) 0000-0002", imagem: "/images/PEDREIRAS.png", maps: "https://maps.app.goo.gl/jEhrQfBDCnTwmxy4A", expandido: false },
        { nome: "PINHEIRO", cnpj:"50.483.492/0011-63", telefone: "(00) 0000-0003", imagem: "/images/PINHEIRO.png", maps: "https://maps.app.goo.gl/WdYBYVsdzowiWJ2fA", expandido: false },
        { nome: "PRESIDENTE DUTRA", cnpj:"50.483.492/0012-44", telefone: "(00) 0000-0004", imagem: "/images/PRESIDENTEDUTRA.png", maps: "https://maps.app.goo.gl/aqqdhyP48Q81d3x7A", expandido: false },
        { nome: "SANTA INÊS", cnpj:"50.483.492/0004-34", telefone: "(00) 0000-0005", imagem: "/images/SANTAINES.png", maps: "https://maps.app.goo.gl/V7znx2rd95qPD8658", expandido: false },
        { nome: "SÃO JOÃO DOS PATOS", cnpj:"50.483.492/0007-87", telefone: "(00) 0000-0006", imagem: "/images/SAOJOAODOSPATOS.png", maps: "https://maps.app.goo.gl/mkauzJnJHU3mZF6q8", expandido: false },
        { nome: "SÃO LUÍS", cnpj:"50.483.492/0001-91", telefone: "(00) 0000-0007", imagem: "/images/SAOLUIS.png", maps: "https://maps.app.goo.gl/pnotcBGBqdpGFbeM6", expandido: false },
        { nome: "TIMON", cnpj:"50.483.492/0005-15", telefone: "(00) 0000-0008", imagem: "/images/TIMON.png", maps: "https://maps.app.goo.gl/6bhtymL8vfMSeZtg8", expandido: false },
      ],
      faqs: [
        {
          question: "Como realizar a troca das placas antigas para o modelo padrão Mercosul?",
          answer: "Para trocar as placas, primeiro faça a vistoria em uma empresa credenciada pelo DETRAN/MA. Depois, solicite a mudança no DETRAN apresentando os documentos exigidos. Com a guia de emplacamento em mãos, vá até uma de nossas lojas para a estampagem e instalação, finalizando o processo com a emissão do CRLV."
        },
        {
          question: "Perdi uma das placas, como solicitar outra?",
          answer: "Se perdeu uma placa, faça a vistoria em uma empresa credenciada e solicite a substituição no DETRAN com os documentos necessários. Após pagar a taxa e obter a guia de emplacamento, basta ir a uma de nossas lojas para a confecção e instalação imediata da nova placa."
        },
        {
          question: "Como funciona o primeiro emplacamento?",
          answer: "O primeiro emplacamento agora é 100% digital e deve ser iniciado pelo site www.emplacamentoma.com.br. É necessário ter a nota fiscal do veículo e realizar o reconhecimento facial do proprietário. Para mais segurança, recomendamos o auxílio de um despachante credenciado."
        },
        {
          question: "Posso instalar a placa em casa?",
          answer: "Não. A instalação deve ser feita por um profissional credenciado, pois o DETRAN exige validações de segurança antes de emitir o CRLV. Além disso, o processo inclui reconhecimento facial e verificação via inteligência artificial. Mas não se preocupe, é rápido e você sai da loja com o documento pronto."
        },
        {
          question: "Apenas o proprietário pode solicitar uma nova placa?",
          answer: "Não necessariamente. O proprietário pode ser representado por procuração, que pode ser particular para parentes de primeiro grau e despachantes credenciados, ou pública para os demais casos. A procuração deve conter os dados do veículo e a especificação do serviço, conforme a portaria 767/2015 do DETRAN/MA."
        },
        {
          question: "Ainda é possível obter as placas cinzas?",
          answer: "Não. Com a adoção do padrão Mercosul, a fabricação e estampagem das placas antigas foram proibidas. No entanto, veículos que já possuem placas cinza em bom estado não precisam trocá-las, salvo em casos de perda, dano ou furto, quando a substituição será obrigatória."
        },
        {
          question: "A placa é entregue na hora?",
          answer: "Sim. A estampagem e instalação são rápidas, mas só podem ser feitas após a emissão da Guia de Emplacamento pelo DETRAN/MA. Em alguns casos, é necessário que o processo esteja auditado. Após a instalação, as validações de segurança são realizadas e o CRLV é emitido automaticamente."
        },
        {
          question: "Quais são as formas de pagamento aceitas?",
          answer: "Aceitamos cartão de débito, crédito (parcelado em até 3x sem juros), boleto bancário e PIX. De acordo com as portarias 618/2021 e 537/2023, os pagamentos devem ser rastreáveis, portanto, não aceitamos dinheiro em espécie."
        },
      ],
      selectedFaq: null
    };
  },

  mounted() {
    // Código do IntersectionObserver
    const observer = new IntersectionObserver((entries) => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          entry.target.classList.add("fade-in-visible");
        }
      });
    });
    document.querySelectorAll(".fade-in").forEach(el => observer.observe(el));
  },

  methods: {
    toggleCard(index) {
      // Alterna a exibição do card
      this.lojas[index].expandido = !this.lojas[index].expandido;
    },
    openModal(faq) {
      this.selectedFaq = faq;
    },
    closeModal() {
      this.selectedFaq = null;
    }
  
  
  }
   
};

// const produtos = [
//   { nome: "Par de placas Mercosul", valor: "R$ 300,00", descricao: "Placas Mercosul de alta qualidade, resistentes e em conformidade com as normas legais, garantindo segurança e visibilidade para o seu veículo.", imagem: "/images/par.png" },
//   { nome: "Unidade Carro Mercosul", valor: "R$ 180,00", descricao: "Placa para carro, com tecnologia avançada e alta resistência. Atende todos os requisitos legais, oferecendo um produto seguro e durável.", imagem: "/images/unidade.png" },
//   { nome: "Unidade Moto Mercosul", valor: "R$ 180,00", descricao: "Placa para moto, com excelente visibilidade e resistência. Totalmente conforme as exigências legais, oferecendo segurança e durabilidade.", imagem: "/images/moto.png" },
//   { nome: "Par Preta Carro Mercosul", valor: "R$ 350,00", descricao: "Placa preta fosca para carro, com design moderno e materiais de alta qualidade. Resistente e conforme as normas do Mercosul, oferece um visual elegante e seguro.", imagem: "/images/parcolecao.png" },
//   { nome: "Unidade Preta Moto Mercosul", valor: "R$ 200,00", descricao: "Placa preta fosca para moto, com design discreto e durável. Conformidade com as normas legais e segurança garantida.", imagem: "/images/unidadecolecao.png" },
//   { nome: "Par Reduzida Carro Mercosul", valor: "R$ 380,00", descricao: "Placa reduzida para carro, compacta e eficiente. Oferece praticidade, segurança e está em conformidade com as normas do Mercosul.", imagem: "/images/par.png" }
// ];
  
  </script>
  
  <style>
  @import url("https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css");
  body {
  font-family: sans-serif;
}
.fade-in {
  opacity: 0;
  transform: translateY(50px);
  transition: opacity 0.6s ease-out, transform 0.6s ease-out;
}
.fade-in-visible {
  opacity: 1;
  transform: translateY(0);
}
nav ul li a {
  transition: color 0.3s;
}
nav ul li a:hover {
  color: #e60000;
}

.fade-in {
  opacity: 0;
  transform: translateY(50px);
  transition: opacity 0.6s ease-out, transform 0.6s ease-out;
}

.fade-in-visible {
  opacity: 1;
  transform: translateY(0);
}

/* Efeito de aumentar o card quando o mouse passa sobre ele */
.hover\:scale-105:hover {
  transform: scale(1.05);
}

/* Suaviza o efeito de sombra */
.hover\:shadow-2xl:hover {
  box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
}

nav ul li a {
  transition: color 0.3s;
}

nav ul li a:hover {
  color: #e60000;
}

 /* Efeito para abertura e fechamento de card de dúvidas frequentes no 12*/

.modal-enter-active, .modal-leave-active {
  transition: opacity 0.5s, transform 0.5s ease;
}
.modal-enter, .modal-leave-to /* .modal-leave-active em <2.1.8 */ {
  opacity: 0;
  transform: translateY(-30px); /* Deslizar para cima ao abrir */
}

.modal-leave-to {
  opacity: 0;
  transform: translateY(30px); /* Deslizar para baixo ao fechar */
}

/* Efeito para desvanecer a opacidade e mover o modal para a posição inicial */
.modal-enter-to {
  opacity: 1;
  transform: translateY(0); /* Modal começa na posição correta ao abrir */
}

  </style>
