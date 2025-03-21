<script setup>
import { Head, Link } from '@inertiajs/vue3';
import { onMounted } from "vue";
import CarAnimation from '@/Components/CarAnimation.vue';
import LinksSection from "@/Components/LinksSection.vue";

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
    <div class="relative min-h-screen">
  <!-- Navbar -->
  <nav
  :class="{
    'bg-transparent': isAtTop,
    'bg-white shadow-lg text-black': !isAtTop,
    'text-white': isAtTop && !isMobile,
    'text-red-500': !isAtTop 
  }"
  class="fixed w-full top-0 z-50 backdrop-blur-md transition-all duration-300"
>
  <div class="container mx-auto flex justify-between items-center py-4 px-6">
    <!-- Logo -->
    <div class="logo-container flex justify-center items-center">
    <img 
        src="/images/logo.jpg" 
        alt="Logo da Empresa" 
        class="w-16 md:w-20 h-auto rounded-lg shadow-md" 
    />
</div>


    <!-- Botão de Menu (Mobile) -->
    <button @click="showMenuMobile = !showMenuMobile" class="block md:hidden text-gray-900 focus:outline-none">
  <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7" />
  </svg>
</button>

    <!-- Menu Desktop -->
    <ul class="hidden md:flex space-x-12 text-lg font-bold">
      <li><a href="#home" @click="scrollToSection($event, 'home')" class="hover:text-red-600">Início</a></li>
      <li><a href="#sobre" @click="scrollToSection($event, 'sobre')" class="hover:text-red-600">Quem Somos</a></li>
      <li><a href="#produtos" @click="scrollToSection($event, 'produtos')" class="hover:text-red-600">Produtos</a></li>
      <li><a href="#lojas" @click="scrollToSection($event, 'lojas')" class="hover:text-red-600">Nossas Lojas</a></li>
      <li><a href="#duvidas" @click="scrollToSection($event, 'duvidas')" class="hover:text-red-600">Dúvidas</a></li>
      <li><a href="#links" @click="scrollToSection($event, 'links')" class="hover:text-red-600">Muito Mais</a></li>
    </ul>

      <!-- Menu Mobile -->
      <transition name="fade">
  <ul v-if="showMenuMobile" class="absolute left-0 top-full w-full bg-white shadow-md flex flex-col items-center space-y-4 py-4 md:hidden">
    <li><a href="#home" @click="scrollToSection($event, 'home'); showMenuMobile = false" class="hover:text-red-500">Início</a></li>
    <li><a href="#sobre" @click="scrollToSection($event, 'sobre'); showMenuMobile = false" class="hover:text-red-500">Quem Somos</a></li>
    <li><a href="#produtos" @click="scrollToSection($event, 'produtos'); showMenuMobile = false" class="hover:text-red-500">Produtos</a></li>
    <li><a href="#lojas" @click="scrollToSection($event, 'lojas'); showMenuMobile = false" class="hover:text-red-500">Nossas Lojas</a></li>
    <li><a href="#duvidas" @click="scrollToSection($event, 'duvidas'); showMenuMobile = false" class="hover:text-red-500">Dúvidas Frequentes</a></li>
    <li><a href="#links" @click="scrollToSection($event, 'links'); showMenuMobile = false" class="hover:text-red-500">Muito Mais</a></li>

    <a href="/login" class="bg-red-500 text-white py-2 px-4 rounded-lg hover:bg-red-900">Área Restrita</a>
  </ul>
</transition>

      <!-- Botão Login -->
      <a href="/login" class="hidden md:block bg-red-500 text-white py-2 px-4 rounded-lg hover:bg-red-700 flex items-center space-x-2">
    <i class="fas fa-lock"></i>
    <span>Área Restrita</span>
</a>
    </div>
  </nav>

  <!-- Seção Home -->
  <section
    id="home"
    class="relative flex flex-wrap items-center justify-center text-gray-900 overflow-hidden h-screen py-20 bg-cover bg-center"
    :style="{ backgroundImage: 'url(/images/background.jpg)', backgroundSize: 'cover', backgroundPosition: 'center' }"
  >
    <div class="container mx-auto px-4 md:px-64 flex flex-col-reverse md:flex-row items-center gap-12 md:gap-16 max-w-[90%]">
      <!-- Texto e Botão -->
      <div class="text-center md:text-left w-full md:w-1/2 text-white">
        <h1 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold font-bold text-white">
          PLACA MERCOSUL<br> É NA NOVA ERA!
        </h1>
        <br /><br />
        <ul class="text-left text-lg sm:text-xl lg:text-3xl mb-6 space-y-2 ml-0">
          <li class="font-bold"> <span class="text-green-500">✓</span>  MELHOR QUALIDADE </li>
          <li class="font-bold"> <span class="text-green-500">✓</span>  MAIS AGILIDADE</li>
          <li class="font-bold"> <span class="text-green-500">✓</span>  MAIOR SEGURANÇA </li>
          <li class="font-bold"> <span class="text-green-500">✓</span>  MAIOR REDE DO BRASIL </li>
        </ul>
        <br />
        <p class="text-lg sm:text-xl lg:text-2xl mb-6 font-bold">
          📍 Peça a sua placa Mercosul com quem entende do assunto!
        </p>
        <a
          href="https://wa.me/559884209099"
          target="_blank"
          class="bg-green-600 font-bold hover:bg-green-900 text-white font-bold py-3 px-6 rounded-lg shadow-lg transition-transform transform hover:scale-110 block md:inline-block"
        >
          Fale Conosco no WhatsApp
        </a>
      </div>
    </div>
  </section>

  </div>

       <!-- Sobre -->
       <section id="sobre" class="py-20 px-6 md:px-16 text-center fade-in bg-white-100 container mx-auto">
  <h2 class="text-4xl font-bold mb-4">Sobre Nós</h2><br><br><br>

  <div class="flex flex-col md:flex-row items-center justify-center gap-12">
    <!-- Imagem da esquerda mais para a esquerda -->
    <div class="w-full md:w-1/3 flex justify-center">
      <img src="../../../public/images/foto2.jpg" alt="Imagem" class="w-3/4 max-w-md h-auto rounded-full shadow-lg object-contain">
    </div>

    <!-- Texto + imagens abaixo centralizados -->
    <div class="w-full md:w-3/5 lg:w-2/3 p-8 text-center md:text-left max-w-4xl mx-auto flex flex-col items-center">
      <h2 class="text-2xl font-bold text-gray-800 text-center">Qualidade e Compromisso em Todo Lugar</h2>
      <p class="mt-4 text-lg text-gray-600 font-bold text-xl">
        Com mais de 25 anos de experiência, a Nova Era Placas se consolidou como referência em qualidade, compromisso e transparência no fornecimento de placas de identificação veicular.
        Contamos com lojas espalhadas por todo o Maranhão e também fora do estado, garantindo que nossos clientes tenham acesso ao melhor em placas veiculares, onde quer que estejam.
        Nossa equipe altamente capacitada oferece um atendimento ágil e personalizado.
        Trabalhamos com responsabilidade para entregar soluções seguras e de alto padrão, sempre alinhadas às normas mais exigentes do setor.  
      </p><br>

      <h2 class="text-2xl font-bold text-gray-800 text-center">Excelência Garantida</h2>
      <p class="mt-4 text-lg text-gray-600 font-bold text-xl">
        Nossas placas são produzidas com matéria-prima de alto padrão. Possuímos o selo ISO 9001, certificação internacional que atesta excelência na gestão da qualidade. Além disso, seguimos rigorosamente a norma *ISO 7591*, que define os padrões para placas de veículos automotores e reboques, garantindo durabilidade, segurança e conformidade com as regulamentações vigentes.
      </p><br>
      
      <!-- Imagens abaixo do texto -->
      <div class="flex flex-col items-center py-10 bg-white-100">
        <div class="flex flex-col md:flex-row items-center justify-center gap-8">
          <img src="../../../public/images/selo.png" alt="Imagem 1" class="h-80 w-80 object-contain sm:h-96 sm:w-96 md:h-64 md:w-64">
          <img src="../../../public/images/iso.png" alt="Imagem 2" class="h-80 w-80 object-contain sm:h-96 sm:w-96 md:h-64 md:w-64">
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
              <p v-if="produto.exibir_valor" class="text-lg font-bold text-blue-600 mt-2">{{ produto.valor }}</p>
            </div>
          </div>
        </div>
      </div>
      </section>
  
      <!-- Lojas -->
      
    <section id="lojas" class="py-20 px-6 text-center fade-in">
      <h2 class="text-3xl font-bold">Onde Estamos</h2>
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mt-8">
        
        <div v-for="(loja, index) in estampadoras" :key="index" 
             class="relative bg-white p-4 rounded-lg shadow-lg overflow-hidden hover:scale-105 hover:shadow-2xl transition-all duration-300" 
             @click="toggleCard(index)">
          <img :src="loja.imagem" alt="Loja" class="w-full h-auto object-contain rounded-t-lg">
          <div class="p-4" :class="{'h-32': !loja.expandido, 'h-auto': loja.expandido}">
            <h3 class="text-xl font-semibold text-red-600">{{ loja.nome }}</h3>
            <p class="text-sm text-gray-700 mt-2 text-gray-400">CNPJ: {{ loja.cnpj_formatado }}</p>
            <p class="text-sm text-gray-700 text-gray-400">Telefone: <a :href="'https://wa.me/' + formatarNumero(loja.telefone)" target="_blank" class="text-red-600 underline" >{{ loja.telefone_formatado }}</a></p>
            <p class="text-sm text-gray-700 text-gray-400">Localização: 
              <a :href="'https://www.google.com/maps?q=' + loja.endereco.latitude + ',' + loja.endereco.longitude" target="_blank" class="text-red-600 underline">Ver no Maps</a>
            </p>
          </div>
        </div>
        
      </div>
    </section>
  
  
     
    <!-- Cards de Dúvidas Frequentes -->
    <section id="duvidas" class="py-20 px-6 bg-gray-100">
    <h2 class="text-3xl font-bold text-center mb-8">Dúvidas Frequentes</h2>
    <div class="flex flex-col items-center gap-6">
  <div v-for="(faq, index) in faqs" :key="index" class="w-full max-w-[100rem]">
    <div
      class="bg-white shadow-lg rounded-lg p-6 cursor-pointer hover:scale-105 hover:shadow-2xl"
      @click="openModal(faq)"
    >
      <h3 class="text-xl text-center font-semibold text-gray-800">{{ faq.question }}</h3>
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
  
      <!-- Links -->
      <section id="links" class="py-20 bg-white-100 px-6 text-center fade-in">
        <LinksSection :links="meusLinks" />
        
<p class="mt-6 text-sm text-gray-600 text-center">
      <strong>Aviso de Isenção de Responsabilidade:</strong> Este site não possui qualquer vínculo com o Departamento Estadual de Trânsito (DETRAN) ou qualquer outro órgão governamental. Os links aqui disponibilizados são de fontes oficiais e têm o propósito de facilitar o acesso a informações públicas. Recomendamos sempre conferir diretamente nos sites oficiais para garantir a veracidade das informações.
    </p><br>
    <p>Para acessar o site oficial do Detran, <a href="https://www.detran.ma.gov.br/inicio/paginas/Home.xhtml" target="_blank" rel="noopener noreferrer" class="text-red-500 hover:underline">Clique Aqui</a>.</p>
</section>
  
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
    
  </template>
  
  <script>


  export default {
    //components: { CarAnimation },
    components: { LinksSection },
  data() {

    return {
      isAtTop: true,
      isMobile: false,
      showMenuMobile: false,
      
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


      meusLinks: [
        { label: "Emplacamento Digital", url: "https://www.detran.ma.gov.br/inicio/paginas/Pagina.xhtml?id=23611" },
        { label: "Portarias", url: "https://www.detran.ma.gov.br/inicio/paginas/Portarias.xhtml" },
        { label: "Parcelamento de débitos", url: "https://www.detran.ma.gov.br/inicio/paginas/Pagina.xhtml?id=22878" },
        { label: "Vistoria", url: "https://www.detran.ma.gov.br/inicio/paginas/Pagina.xhtml?id=23581" },
        { label: "Credenciados", url: "https://www.detran.ma.gov.br/inicio/paginas/Credenciado.xhtml" },
        { label: "Ciretrans", url: "https://www.detran.ma.gov.br/inicio/paginas/ListarCiretrans.xhtml" },
        { label: "Multas PRF", url: "https://nadaconsta.prf.gov.br/nada_consta/index.jsf" },
        { label: "Multas DNIT", url: "https://servicos.dnit.gov.br/multas/" },
        { label: "CEP dos Municípios", url: "https://buscacepinter.correios.com.br/app/endereco/index.php" },

      ],
      selectedFaq: null
    };
  },


  
  mounted() {

    window.addEventListener('resize', this.checkDevice);
    window.addEventListener('scroll', this.handleScroll);
    this.checkDevice();

    // Código do IntersectionObserver
    const observer = new IntersectionObserver((entries) => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          entry.target.classList.add("fade-in-visible");
        }
      });
    });

    document.querySelectorAll(".fade-in").forEach(el => observer.observe(el));

    //animação tela inicial
    setTimeout(() => {
      this.$refs.textBlock.classList.remove("opacity-0", "translate-y-10");
      this.$refs.imageBlock.classList.remove("opacity-0", "translate-x-10");
    }, 300);
  },

  beforeUnmount() {
    window.removeEventListener("scroll", this.checkScroll);
  },

  beforeDestroy() {
    // Remove o evento de rolagem quando o componente for destruído
    window.removeEventListener('scroll', this.handleScroll);
    window.removeEventListener('resize', this.checkDevice);
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
    },

    scrollToSection(event, section) {
      event.preventDefault();
      const targetElement = document.getElementById(section);
      if (targetElement) {
        targetElement.scrollIntoView({ behavior: "smooth" });
      }

      // Fecha o menu mobile após o clique (se estiver aberto)
      if (this.showMenuMobile) {
        this.showMenuMobile = false;
      }

    // Função para alternar a visibilidade do menu mobile
    toggleMenuMobile(); {
      this.showMenuMobile = !this.showMenuMobile;
    }
  
    },
    checkScroll() {
      this.isAtTop = window.scrollY < 50;
    },

    handleScroll() {
      this.isAtTop = window.scrollY === 0;
    },

    checkDevice() {
      this.isMobile = window.innerWidth <= 768;
    }
  
  
  },
   
};

const formatarNumero = (numero) => {
  if (!numero) return "";
  return numero.replace(/\D/g, ""); // Remove tudo que não for número
};
  
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

/*tela inicial*/
@keyframes spin-slow {
  from { transform: rotate(0deg); }
  to { transform: rotate(360deg); }
}
.animate-spin-slow {
  animation: spin-slow 10s linear infinite;
}

@keyframes fade-in {
  from { opacity: 0; }
  to { opacity: 1; }
}
.animate-fade-in {
  animation: fade-in 1.5s ease-out;
}

@keyframes slide-in {
  from { opacity: 0; transform: translateY(20px); }
  to { opacity: 1; transform: translateY(0); }
}
.animate-slide-in {
  animation: slide-in 1.5s ease-out;
}

@keyframes wiggle {
  0%, 100% { transform: rotate(-3deg); }
  50% { transform: rotate(3deg); }
}
.animate-wiggle {
  animation: wiggle 0.3s ease-in-out infinite;
}

@keyframes zoom-in {
  from { transform: scale(0.8); opacity: 0; }
  to { transform: scale(1); opacity: 1; }
}
.animate-zoom-in {
  animation: zoom-in 1.5s ease-out;
}
  </style>