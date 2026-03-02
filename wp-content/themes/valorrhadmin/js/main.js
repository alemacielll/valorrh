/**
 * Main JavaScript File - Valor RH
 * Versão Consolidada com todas as funções de clonagem (Formação, Job, Idiomas)
 */

// 1. Funções Globais (necessárias para onclick no HTML)
window.toggleView = function(hideId, showId) {
    const hideEl = document.getElementById(hideId);
    const showEl = document.getElementById(showId);
    
    if(hideEl) hideEl.classList.add('hidden');
    if(showEl) showEl.classList.remove('hidden');
    
    // Se estiver voltando para a lista, reinicializa ícones caso necessário
    if(showId === 'view-list') {
        if (typeof lucide !== 'undefined') lucide.createIcons();
    }
};

document.addEventListener('DOMContentLoaded', () => {
    // 2. Inicialização de Ícones
    if (typeof lucide !== 'undefined') lucide.createIcons();

    // 3. Lógica do Gráfico (Dashboard)
    const ctx = document.getElementById('candidatesChart');
    if (ctx && typeof Chart !== 'undefined') {
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun'],
                datasets: [{
                    label: 'Novos Candidatos',
                    data: [45, 72, 58, 90, 120, 85],
                    backgroundColor: '#3b82f6',
                    borderRadius: 4,
                    barThickness: 40
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    y: { display: false, grid: { display: false } },
                    x: { grid: { display: false }, ticks: { color: '#64748b' } }
                }
            }
        });
    }

    // 4. Lógica de Abas (Tabs)
    const tabs = document.querySelectorAll('[data-tab-target]');
    tabs.forEach(tab => {
        tab.addEventListener('click', () => {
            const targetSelector = tab.dataset.tabTarget;
            const target = document.querySelector(targetSelector);
            const tabContainer = tab.parentElement;
            const allTabs = tabContainer.querySelectorAll('[data-tab-target]');
            
            allTabs.forEach(t => {
                t.classList.remove('active', 'border-indigo-600', 'text-indigo-600');
                t.classList.add('border-transparent', 'text-slate-500');
            });

            tab.classList.add('active', 'border-indigo-600', 'text-indigo-600');
            tab.classList.remove('border-transparent', 'text-slate-500');

            allTabs.forEach(t => {
                const s = t.dataset.tabTarget;
                const el = document.querySelector(s);
                if (el) el.classList.add('hidden');
            });
            
            if(target) target.classList.remove('hidden');
        });
    });

    // 5. Sidebar Mobile Toggle
    const sidebarToggle = document.getElementById('sidebarToggle');
    const sidebar = document.getElementById('sidebar');
    const sidebarOverlay = document.getElementById('sidebarOverlay');

    if (sidebarToggle && sidebar && sidebarOverlay) {
        sidebarToggle.addEventListener('click', () => {
            sidebar.classList.add('show');
            sidebarOverlay.classList.add('show');
        });
        sidebarOverlay.addEventListener('click', () => {
            sidebar.classList.remove('show');
            sidebarOverlay.classList.remove('show');
        });
    }

    // 6. Kanban Logic
    const kanbanColumns = document.querySelectorAll('.kanban-col .flex-grow-1'); 
    if (kanbanColumns.length > 0 && typeof Sortable !== 'undefined') {
        kanbanColumns.forEach(col => {
            new Sortable(col, {
                group: 'kanban',
                animation: 150,
                ghostClass: 'bg-indigo-50',
                dragClass: 'opacity-50',
                onEnd: function () {
                    updateKanbanCounters();
                }
            });
        });
        updateKanbanCounters();
    }

    // 7. Edit Mode Listeners
    const editButton = document.getElementById('btn-edit-info');
    const saveButton = document.getElementById('btn-save-info');

    if (editButton) editButton.addEventListener('click', toggleEditMode);
    if (saveButton) saveButton.addEventListener('click', saveChanges);
});

/* --- Funções Auxiliares e Lógica de Edição --- */

function updateKanbanCounters() {
    const cols = document.querySelectorAll('.kanban-col');
    cols.forEach(col => {
        const cardCount = col.querySelectorAll('.kanban-card').length;
        const counter = col.querySelector('.kanban-counter');
        if (counter) counter.innerText = cardCount;
    });
}

// Variável de controle
let isEditMode = false;

function toggleEditMode() {
    const editButton = document.getElementById('btn-edit-info');
    const saveButton = document.getElementById('btn-save-info');

    isEditMode = true;
    editButton.classList.add('d-none');
    saveButton.classList.remove('d-none');
    
    // 1. Transformar campos existentes em inputs
    const editables = document.querySelectorAll('[data-editable]');
    editables.forEach((el) => {
        makeElementEditable(el);
    });

    // 2. Adicionar botões "+" nas listas
    const lists = document.querySelectorAll('[data-editable-list]');
    lists.forEach(container => {
        const type = container.dataset.editableList;
        
        // Criar botão Add
        const addBtn = document.createElement('button');
        addBtn.className = 'btn-add-item mt-2';
        addBtn.innerHTML = '<i data-lucide="plus" width="10"></i> +'; 
        // Estilo básico para o botão ficar bonito
        addBtn.style.cssText = "border: 1px dashed #cbd5e1; background: #f8fafc; color: #64748b; padding: 4px 10px; border-radius: 50px; font-size: 1rem; cursor: pointer; display: inline-flex; align-items: center; gap: 4px;";
        
        addBtn.type = 'button';
        
        addBtn.onclick = function() {
            addNewItem(container, type, addBtn);
        };
        
        // Se for UL, coloca dentro de um LI para manter semântica, senão (DIV) coloca direto
        if (container.tagName === 'UL') {
            const li = document.createElement('li');
            li.style.listStyle = 'none';
            li.className = 'mt-3'; // Espaçamento
            li.appendChild(addBtn);
            container.appendChild(li);
        } else {
            container.appendChild(addBtn);
        }
    });

    if (typeof lucide !== 'undefined') lucide.createIcons();
}

function makeElementEditable(el) {
    // Evita duplicar input se já existir
    if (el.querySelector('input') || el.querySelector('textarea')) return;

    const currentText = el.innerText.trim();
    let input;
    
    // Verifica se precisa ser textarea (texto longo)
    if (el.tagName === 'P' && (getComputedStyle(el).whiteSpace === 'pre-line' || currentText.length > 50)) {
         input = document.createElement('textarea');
         input.rows = 3;
    } else {
         input = document.createElement('input');
         input.type = 'text';
    }
    
    input.value = currentText;
    input.className = 'editable-input form-control form-control-sm';
    
    // Estilo especial para Badges
    if (el.closest('.badge')) {
        input.classList.add('badge-input');
        input.classList.remove('form-control', 'form-control-sm'); 
        input.style.cssText = "display: inline-block; width: auto; min-width: 60px; padding: 0 4px; height: 1.4em; font-size: inherit; color: #333; background: rgba(255,255,255,0.9); border: none; outline: none; border-radius: 2px;";
    }
    
    el.innerHTML = '';
    el.appendChild(input);
    input.addEventListener('click', (e) => e.stopPropagation());
}

/**
 * Função Principal de Clonagem
 * Gerencia Formação, Experiência, Idiomas e Listas Simples
 */
function addNewItem(container, type, beforeElement) {
    let newItem;

    // 1. FORMAÇÃO ACADÊMICA
    if (type === 'academic-entry') {
        const templateItem = container.querySelector('li.entry-item');
        if (!templateItem) return;

        newItem = templateItem.cloneNode(true);
        const fields = newItem.querySelectorAll('[data-editable]');
        
        fields.forEach(field => {
            field.innerHTML = ''; 
            if (field.classList.contains('badge')) field.innerText = 'Status';
            else if (field.parentElement.querySelector('label')?.innerText.includes('Início')) field.innerText = 'MM/AAAA';
            else if (field.parentElement.querySelector('label')?.innerText.includes('Conclusão')) field.innerText = 'MM/AAAA';
            else field.innerText = 'Preencher...'; 
        });

        // Inserir antes do LI que contém o botão
        container.insertBefore(newItem, beforeElement.parentElement);
        fields.forEach(field => makeElementEditable(field));

    // 2. EXPERIÊNCIA PROFISSIONAL
    } else if (type === 'job-entry') {
        const templateItem = container.querySelector('li.job-item');
        if (!templateItem) return;

        newItem = templateItem.cloneNode(true);
        const fields = newItem.querySelectorAll('[data-editable]');

        fields.forEach(field => {
            field.innerHTML = '';
            
            // Tentativa de identificar o campo pelo contexto
            if (field.tagName === 'P') {
                field.innerText = 'Descreva suas atividades...';
            } else if (field.parentElement.tagName === 'H4') {
                // Tenta diferenciar Cargo de Empresa pela posição ou classe, ou usa genérico
                if(field.nextElementSibling && field.nextElementSibling.innerText.includes('em')) field.innerText = 'Cargo';
                else field.innerText = 'Empresa';
            } else {
                field.innerText = 'MM/AAAA'; // Datas
            }
        });

        container.insertBefore(newItem, beforeElement.parentElement);
        fields.forEach(field => makeElementEditable(field));

    // 3. IDIOMAS (Container DIV)
    } else if (type === 'language-entry') {
        const templateItem = container.querySelector('.language-item');
        if (!templateItem) return;

        newItem = templateItem.cloneNode(true);
        const fields = newItem.querySelectorAll('[data-editable]');

        fields.forEach(field => {
            field.innerHTML = '';
            // Verifica o label anterior para saber o contexto
            const labelText = field.previousElementSibling?.innerText || '';
            
            if (labelText.includes('Idioma')) field.innerText = 'Novo Idioma';
            else if (labelText.includes('Nível')) field.innerText = 'Nível';
            else field.innerText = '...';
        });

        // Como aqui o container é uma DIV, o botão é filho direto
        container.insertBefore(newItem, beforeElement);
        fields.forEach(field => makeElementEditable(field));

    // 4. BADGES (Skills)
    } else if (type.startsWith('badge')) {
        newItem = document.createElement('span');
        let classes = 'badge rounded-pill fw-medium border ';
        // Detecta cor baseado no tipo
        if (type.includes('red')) classes += 'bg-red-50 text-red-600 border-red-100';
        else if (type.includes('indigo')) classes += 'bg-indigo-50 text-indigo-600 border-indigo-100';
        else classes += 'bg-slate-100 text-slate-600';
        
        newItem.className = classes;
        newItem.setAttribute('data-editable', ''); // Importante para salvar depois
        
        container.insertBefore(newItem, beforeElement);
        makeElementEditable(newItem);
        
        const input = newItem.querySelector('input');
        if(input) {
            input.focus();
            input.value = "Nova Skill";
        }

    // 5. LISTAS SIMPLES (Ex: Benefícios)
    } else if (type === 'benefits' || type === 'list') {
        newItem = document.createElement('li');
        
        if (type === 'benefits') {
            newItem.className = 'd-flex align-items-center gap-2 text-slate-600 mb-2';
            newItem.innerHTML = `<span class="rounded-circle bg-emerald-500" style="width: 6px; height: 6px;"></span><span data-editable>Novo Item</span>`;
        } else {
            newItem.setAttribute('data-editable', '');
            newItem.innerText = 'Novo Item';
        }
        
        container.insertBefore(newItem, beforeElement.parentElement);
        const spanToEdit = newItem.querySelector('[data-editable]') || newItem;
        makeElementEditable(spanToEdit);
    }
    
    // Recria ícones para os novos elementos adicionados
    if (typeof lucide !== 'undefined') lucide.createIcons();
}

function saveChanges() {
    const editButton = document.getElementById('btn-edit-info');
    const saveButton = document.getElementById('btn-save-info');

    isEditMode = false;
    saveButton.classList.add('d-none');
    editButton.classList.remove('d-none');
    
    // 1. Remover botões "+" e seus containers (LIs criados para eles)
    const addBtns = document.querySelectorAll('.btn-add-item');
    addBtns.forEach(btn => {
        const parent = btn.parentElement;
        if (parent.tagName === 'LI' && parent.children.length === 1) {
            parent.remove(); // Remove o LI inteiro se só tiver o botão
        } else {
            btn.remove(); // Remove só o botão (caso de DIVs)
        }
    });

    // 2. Finalizar Inputs e Salvar Texto
    const inputs = document.querySelectorAll('.editable-input, .badge-input');
    inputs.forEach(input => {
        const parent = input.parentElement;
        const newValue = input.value.trim();
        
        // Se estiver vazio, decide se remove o elemento ou deixa vazio
        if (newValue === '') {
            // Remove badges vazios
            if (parent.classList.contains('badge')) {
                parent.remove();
            } 
            // Não removemos campos estruturais (Ex: Cargo), apenas deixamos vazio ou placeholder
            else if (parent.hasAttribute('data-editable')) {
                parent.innerText = '-'; 
            }
        } else {
            parent.innerText = newValue; 
        }
    });
}

document.addEventListener('DOMContentLoaded', function () {
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.forEach(function (el) {
    new bootstrap.Tooltip(el);
    });
});

$(document).ready(function(){
    $('#cnpj').inputmask('99.999.999/9999-99');
	$('#cpf').inputmask('999.999.999-99');
    $('#cep').inputmask('99999-999');
    var phoneMask = function (val) {
        return val.replace(/\D/g, '').length === 11 ? '(99) 99999-9999' : '(99) 9999-9999[9]';
    };
    $('#telefone').inputmask({
        mask: ['(99) 9999-9999', '(99) 99999-9999'],
        keepStatic: true
    });
    $('#celular').inputmask({
        mask: ['(99) 9999-9999', '(99) 99999-9999'],
        keepStatic: true
    });
});

jQuery(document).ready(function ($) {

    function limpa_formulário_cep() {
        $("#logradouro").val("");
        $("#bairro").val("");
        $("#cidade").val("");
        $("#estado").val("");
    }

    $("#cep").on("blur", function () {
        var cep = $(this).val().replace(/\D/g, "");

        if (!cep) return;

        if (!/^[0-9]{8}$/.test(cep)) {
            limpa_formulário_cep();
            alert("Formato de CEP inválido.");
            return;
        }

        $("#logradouro").val("...");
        $("#bairro").val("...");
        $("#cidade").val("...");
        $("#estado").val("...");

        $.ajax({
            url: "https://viacep.com.br/ws/" + cep + "/json/",
            dataType: "json",
            timeout: 8000
        })
        .done(function (dados) {
            if (!dados.erro) {
                $("#logradouro").val(dados.logradouro || "");
                $("#bairro").val(dados.bairro || "");
                $("#cidade").val(dados.localidade || "");
                $("#estado").val(dados.uf || "");
                $("#numero").focus();
            } else {
                limpa_formulário_cep();
                alert("CEP não encontrado.");
            }
        })
        .fail(function (xhr, status, error) {
            limpa_formulário_cep();
            console.error("Falha na requisição ViaCEP:", status, error);
        });
    });

});
