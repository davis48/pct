<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Certificat de Décès | PCT UVCI</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #f8fafc 0%, #e0e7ff 100%);
            color: #1f2937;
            line-height: 1.6;
            overflow-x: hidden;
        }
        
        .navbar {
            background: white;
            border-bottom: 1px solid #e5e7eb;
            padding: 1rem 0;
            position: sticky;
            top: 0;
            z-index: 50;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }
        
        .navbar-content {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 1rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .logo {
            display: flex;
            align-items: center;
            font-weight: 700;
            font-size: 1.25rem;
            color: #2563eb;
        }
        
        .logo i {
            margin-right: 0.5rem;
            font-size: 1.5rem;
        }
        
        .nav-links {
            display: flex;
            gap: 2rem;
            list-style: none;
            align-items: center;
        }
        
        .nav-links a {
            text-decoration: none;
            color: #6b7280;
            font-weight: 500;
            transition: color 0.3s;
        }
        
        .nav-links a:hover {
            color: #2563eb;
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 2rem 1rem;
        }
        
        .page-header {
            text-align: center;
            margin-bottom: 3rem;
        }
        
        .page-title {
            font-size: 2.5rem;
            font-weight: 700;
            color: #1f2937;
            margin-bottom: 1rem;
        }
        
        .page-subtitle {
            font-size: 1.125rem;
            color: #6b7280;
            max-width: 600px;
            margin: 0 auto;
        }
        
        .form-container {
            background: white;
            border-radius: 1rem;
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        
        .form-section {
            padding: 2rem;
            border-bottom: 1px solid #e5e7eb;
        }
        
        .form-section:last-child {
            border-bottom: none;
        }
        
        .section-title {
            display: flex;
            align-items: center;
            font-size: 1.25rem;
            font-weight: 600;
            color: #1f2937;
            margin-bottom: 1.5rem;
        }
        
        .section-title i {
            margin-right: 0.75rem;
            font-size: 1.125rem;
        }
        
        .form-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 1.5rem;
        }
        
        @media (min-width: 768px) {
            .form-grid {
                grid-template-columns: repeat(2, 1fr);
            }
            
            .form-grid.single-column {
                grid-template-columns: 1fr;
            }
        }
        
        .form-group {
            display: flex;
            flex-direction: column;
        }
        
        .form-group.full-width {
            grid-column: 1 / -1;
        }
        
        .form-label {
            font-weight: 500;
            color: #374151;
            margin-bottom: 0.5rem;
            font-size: 0.875rem;
        }
        
        .required {
            color: #ef4444;
        }
        
        .form-input, .form-select, .form-textarea {
            padding: 0.75rem;
            border: 1px solid #d1d5db;
            border-radius: 0.5rem;
            font-size: 0.875rem;
            transition: all 0.3s;
            background: white;
        }
        
        .form-input:focus, .form-select:focus, .form-textarea:focus {
            outline: none;
            border-color: #2563eb;
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
        }
        
        .form-textarea {
            resize: vertical;
            min-height: 100px;
        }
        
        .checkbox-group {
            display: flex;
            align-items: flex-start;
            gap: 0.75rem;
        }
        
        .checkbox-input {
            margin-top: 0.125rem;
            width: 1rem;
            height: 1rem;
            accent-color: #2563eb;
        }
        
        .checkbox-label {
            font-size: 0.875rem;
            color: #6b7280;
            line-height: 1.5;
        }
        
        .button-group {
            display: flex;
            flex-direction: column;
            gap: 1rem;
            margin-top: 2rem;
        }
        
        @media (min-width: 640px) {
            .button-group {
                flex-direction: row;
            }
        }
        
        .btn {
            padding: 0.875rem 1.5rem;
            border: none;
            border-radius: 0.5rem;
            font-weight: 500;
            font-size: 0.875rem;
            cursor: pointer;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            flex: 1;
        }
        
        .btn-primary {
            background: #2563eb;
            color: white;
        }
        
        .btn-primary:hover {
            background: #1d4ed8;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.3);
        }
        
        .btn-secondary {
            background: #059669;
            color: white;
        }
        
        .btn-secondary:hover {
            background: #047857;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(5, 150, 105, 0.3);
        }
        
        .progress-indicator {
            display: flex;
            justify-content: center;
            margin-bottom: 2rem;
        }
        
        .progress-step {
            display: flex;
            align-items: center;
            font-size: 0.875rem;
            color: #6b7280;
        }
        
        .progress-step.active {
            color: #2563eb;
            font-weight: 500;
        }
        
        .progress-step:not(:last-child)::after {
            content: '→';
            margin: 0 1rem;
            color: #d1d5db;
        }
        
        .floating-elements {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            z-index: -1;
        }
        
        .floating-element {
            position: absolute;
            border-radius: 50%;
            background: linear-gradient(45deg, rgba(59, 130, 246, 0.1), rgba(147, 51, 234, 0.1));
            animation: float 6s ease-in-out infinite;
        }
        
        .floating-element:nth-child(1) {
            top: 10%;
            left: 10%;
            width: 80px;
            height: 80px;
            animation-delay: 0s;
        }
        
        .floating-element:nth-child(2) {
            top: 70%;
            right: 10%;
            width: 120px;
            height: 120px;
            animation-delay: 2s;
        }
        
        .floating-element:nth-child(3) {
            bottom: 10%;
            left: 20%;
            width: 60px;
            height: 60px;
            animation-delay: 4s;
        }
        
        @keyframes float {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-20px) rotate(180deg); }
        }
        
        .fade-in {
            animation: fadeIn 0.8s ease-out;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .error-message {
            color: #ef4444;
            font-size: 0.75rem;
            margin-top: 0.25rem;
        }
        
        .success-message {
            background: #d1fae5;
            border: 1px solid #86efac;
            color: #065f46;
            padding: 1rem;
            border-radius: 0.5rem;
            margin-bottom: 1rem;
        }
        
        .death-icon {
            color: #ef4444;
        }
        
        .info-icon {
            color: #2563eb;
        }
        
        @media (max-width: 768px) {
            .page-title {
                font-size: 2rem;
            }
            
            .form-section {
                padding: 1.5rem;
            }
            
            .nav-links {
                display: none;
            }
        }
    </style>
</head>
<body>
    <div class="floating-elements">
        <div class="floating-element"></div>
        <div class="floating-element"></div>
        <div class="floating-element"></div>
    </div>

    <nav class="navbar">
        <div class="navbar-content">
            <div class="logo">
                <i class="fas fa-landmark"></i>
                PCT UVCI
            </div>
            <ul class="nav-links">
                <li><a href="{{ route('home') }}">Accueil</a></li>
                <li><a href="{{ route('interactive-forms.index') }}">Formulaires</a></li>
                <li><a href="#contact">Contact</a></li>
            </ul>
        </div>
    </nav>

    <div class="container">
        <div class="page-header fade-in">
            <h1 class="page-title">
                <i class="fas fa-heart-broken death-icon"></i>
                Certificat de Décès
            </h1>
            <p class="page-subtitle">
                Formulaire de demande de certificat de décès. Veuillez remplir toutes les informations requises avec précision.
            </p>
        </div>

        <div class="progress-indicator fade-in">
            <div class="progress-step active">
                <i class="fas fa-user-times"></i>
                Informations du défunt
            </div>
            <div class="progress-step">
                <i class="fas fa-user-circle"></i>
                Déclarant
            </div>
            <div class="progress-step">
                <i class="fas fa-check-circle"></i>
                Validation
            </div>
        </div>

        <div class="form-container fade-in">
            <form action="{{ route('interactive-forms.generate', 'certificat-deces') }}" method="POST" id="deathCertificateForm">
                @csrf
                
                <!-- Informations du Défunt -->
                <div class="form-section">
                    <h2 class="section-title">
                        <i class="fas fa-user-times death-icon"></i>
                        Informations sur le Défunt
                    </h2>
                    
                    <div class="form-grid">
                        <div class="form-group">
                            <label for="deceased_last_name" class="form-label">
                                Nom(s) du défunt <span class="required">*</span>
                            </label>
                            <input type="text" id="deceased_last_name" name="deceased_last_name" 
                                   value="{{ old('deceased_last_name') }}"
                                   class="form-input" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="deceased_first_name" class="form-label">
                                Prénom(s) du défunt <span class="required">*</span>
                            </label>
                            <input type="text" id="deceased_first_name" name="deceased_first_name" 
                                   value="{{ old('deceased_first_name') }}"
                                   class="form-input" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="deceased_birth_date" class="form-label">
                                Date de naissance du défunt <span class="required">*</span>
                            </label>
                            <input type="date" id="deceased_birth_date" name="deceased_birth_date" 
                                   value="{{ old('deceased_birth_date') }}"
                                   class="form-input" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="deceased_birth_place" class="form-label">
                                Lieu de naissance du défunt <span class="required">*</span>
                            </label>
                            <input type="text" id="deceased_birth_place" name="deceased_birth_place" 
                                   value="{{ old('deceased_birth_place') }}"
                                   class="form-input" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="death_date" class="form-label">
                                Date du décès <span class="required">*</span>
                            </label>
                            <input type="date" id="death_date" name="death_date" 
                                   value="{{ old('death_date') }}"
                                   class="form-input" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="death_place" class="form-label">
                                Lieu du décès <span class="required">*</span>
                            </label>
                            <input type="text" id="death_place" name="death_place" 
                                   value="{{ old('death_place') }}"
                                   class="form-input" required>
                        </div>
                    </div>
                </div>

                <!-- Informations du Déclarant -->
                <div class="form-section">
                    <h2 class="section-title">
                        <i class="fas fa-user-circle info-icon"></i>
                        Informations du Déclarant
                    </h2>
                    
                    <div class="form-grid">
                        <div class="form-group">
                            <label for="declarant_name" class="form-label">
                                Nom et Prénoms du déclarant <span class="required">*</span>
                            </label>
                            <input type="text" id="declarant_name" name="declarant_name" 
                                   value="{{ old('declarant_name', $userData['name'] ?? '') }}"
                                   class="form-input" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="declarant_birth_date" class="form-label">
                                Date de naissance du déclarant <span class="required">*</span>
                            </label>
                            <input type="date" id="declarant_birth_date" name="declarant_birth_date" 
                                   value="{{ old('declarant_birth_date', $userData['date_naissance'] ?? '') }}"
                                   class="form-input" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="declarant_profession" class="form-label">
                                Profession du déclarant
                            </label>
                            <input type="text" id="declarant_profession" name="declarant_profession" 
                                   value="{{ old('declarant_profession', $userData['profession'] ?? '') }}"
                                   class="form-input">
                        </div>
                        
                        <div class="form-group">
                            <label for="declarant_address" class="form-label">
                                Domicile du déclarant <span class="required">*</span>
                            </label>
                            <input type="text" id="declarant_address" name="declarant_address" 
                                   value="{{ old('declarant_address', $userData['address'] ?? '') }}"
                                   class="form-input" required>
                        </div>
                        
                        <div class="form-group full-width">
                            <label for="relationship_to_deceased" class="form-label">
                                Lien avec le défunt <span class="required">*</span>
                            </label>
                            <select id="relationship_to_deceased" name="relationship_to_deceased" 
                                    class="form-select" required>
                                <option value="">Sélectionnez le lien de parenté</option>
                                <option value="Époux/Épouse" {{ old('relationship_to_deceased') == 'Époux/Épouse' ? 'selected' : '' }}>Époux/Épouse</option>
                                <option value="Fils/Fille" {{ old('relationship_to_deceased') == 'Fils/Fille' ? 'selected' : '' }}>Fils/Fille</option>
                                <option value="Père/Mère" {{ old('relationship_to_deceased') == 'Père/Mère' ? 'selected' : '' }}>Père/Mère</option>
                                <option value="Frère/Sœur" {{ old('relationship_to_deceased') == 'Frère/Sœur' ? 'selected' : '' }}>Frère/Sœur</option>
                                <option value="Petit-fils/Petite-fille" {{ old('relationship_to_deceased') == 'Petit-fils/Petite-fille' ? 'selected' : '' }}>Petit-fils/Petite-fille</option>
                                <option value="Neveu/Nièce" {{ old('relationship_to_deceased') == 'Neveu/Nièce' ? 'selected' : '' }}>Neveu/Nièce</option>
                                <option value="Ami(e)" {{ old('relationship_to_deceased') == 'Ami(e)' ? 'selected' : '' }}>Ami(e)</option>
                                <option value="Autre" {{ old('relationship_to_deceased') == 'Autre' ? 'selected' : '' }}>Autre</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Informations complémentaires -->
                <div class="form-section">
                    <h2 class="section-title">
                        <i class="fas fa-info-circle info-icon"></i>
                        Informations Complémentaires
                    </h2>
                    
                    <div class="form-grid single-column">
                        <div class="form-group">
                            <label for="purpose" class="form-label">
                                Motif de la demande <span class="required">*</span>
                            </label>
                            <select id="purpose" name="purpose" class="form-select" required>
                                <option value="">Sélectionnez le motif</option>
                                <option value="Succession" {{ old('purpose') == 'Succession' ? 'selected' : '' }}>Succession</option>
                                <option value="Démarches bancaires" {{ old('purpose') == 'Démarches bancaires' ? 'selected' : '' }}>Démarches bancaires</option>
                                <option value="Assurance" {{ old('purpose') == 'Assurance' ? 'selected' : '' }}>Assurance</option>
                                <option value="Pension" {{ old('purpose') == 'Pension' ? 'selected' : '' }}>Pension</option>
                                <option value="Autre" {{ old('purpose') == 'Autre' ? 'selected' : '' }}>Autre</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="notes" class="form-label">
                                Remarques ou informations complémentaires
                            </label>
                            <textarea id="notes" name="notes" rows="3"
                                      class="form-textarea"
                                      placeholder="Toute information complémentaire utile...">{{ old('notes') }}</textarea>
                        </div>
                    </div>
                </div>

                <!-- Déclaration et soumission -->
                <div class="form-section">
                    <div class="checkbox-group">
                        <input type="checkbox" id="declaration" name="declaration" 
                               class="checkbox-input" required>
                        <label for="declaration" class="checkbox-label">
                            Je déclare sur l'honneur que les informations fournies sont exactes et complètes. Je suis conscient(e) que toute fausse déclaration peut entraîner des poursuites judiciaires. <span class="required">*</span>
                        </label>
                    </div>

                    <div class="button-group">
                        <button type="submit" name="action" value="preview" class="btn btn-primary">
                            <i class="fas fa-eye"></i>
                            Prévisualiser le document
                        </button>
                        
                        <button type="submit" name="action" value="generate" class="btn btn-secondary">
                            <i class="fas fa-download"></i>
                            Générer le document
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Animation des éléments au scroll
            const observerOptions = {
                threshold: 0.1,
                rootMargin: '0px 0px -50px 0px'
            };

            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.style.opacity = '1';
                        entry.target.style.transform = 'translateY(0)';
                    }
                });
            }, observerOptions);

            document.querySelectorAll('.form-section').forEach(section => {
                section.style.opacity = '0';
                section.style.transform = 'translateY(20px)';
                section.style.transition = 'all 0.6s ease-out';
                observer.observe(section);
            });

            // Validation des dates
            const deathDate = document.getElementById('death_date');
            const birthDate = document.getElementById('deceased_birth_date');
            
            function validateDates() {
                if (birthDate.value && deathDate.value) {
                    const birth = new Date(birthDate.value);
                    const death = new Date(deathDate.value);
                    
                    if (death <= birth) {
                        deathDate.setCustomValidity('La date de décès doit être postérieure à la date de naissance');
                    } else {
                        deathDate.setCustomValidity('');
                    }
                }
            }
            
            birthDate.addEventListener('change', validateDates);
            deathDate.addEventListener('change', validateDates);
            
            // Validation de la date de décès (ne peut pas être dans le futur)
            deathDate.addEventListener('change', function() {
                const today = new Date();
                const selectedDate = new Date(this.value);
                
                if (selectedDate > today) {
                    this.setCustomValidity('La date de décès ne peut pas être dans le futur');
                } else {
                    this.setCustomValidity('');
                }
                validateDates();
            });

            // Mise à jour de l'indicateur de progression
            const progressSteps = document.querySelectorAll('.progress-step');
            const formSections = document.querySelectorAll('.form-section');

            const updateProgress = () => {
                let completedSections = 0;
                
                formSections.forEach((section, index) => {
                    const requiredInputs = section.querySelectorAll('input[required], select[required]');
                    const filledInputs = Array.from(requiredInputs).filter(input => 
                        input.value.trim() !== ''
                    );
                    
                    if (filledInputs.length === requiredInputs.length) {
                        completedSections++;
                    }
                });

                progressSteps.forEach((step, index) => {
                    step.classList.toggle('active', index <= completedSections);
                });
            };

            // Écouter les changements dans tous les champs requis
            document.querySelectorAll('input[required], select[required]').forEach(input => {
                input.addEventListener('input', updateProgress);
                input.addEventListener('change', updateProgress);
            });

            // Animation des boutons
            document.querySelectorAll('.btn').forEach(button => {
                button.addEventListener('mouseenter', function() {
                    this.style.transform = 'translateY(-2px)';
                });
                
                button.addEventListener('mouseleave', function() {
                    this.style.transform = 'translateY(0)';
                });
            });

            // Validation du formulaire avant soumission
            document.getElementById('deathCertificateForm').addEventListener('submit', function(e) {
                const declaration = document.getElementById('declaration');
                
                if (!declaration.checked) {
                    e.preventDefault();
                    alert('Veuillez accepter la déclaration sur l\'honneur pour continuer.');
                    declaration.focus();
                    return false;
                }
            });
        });
    </script>
</body>
</html>
