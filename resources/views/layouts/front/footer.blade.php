<footer class="bg-gray-900 text-white py-12 mt-auto">
    <div class="container mx-auto px-4">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
            <!-- Logo et description -->
            <div class="lg:col-span-2">
                <div class="flex items-center space-x-3 mb-4">
                    <i class="fas fa-landmark text-3xl text-primary-400"></i>
                    <h5 class="text-xl font-bold">Plateforme d'Actes Civils</h5>
                </div>
                <p class="text-gray-300 mb-6 leading-relaxed">Simplifiez vos démarches administratives en ligne. Notre plateforme vous permet de demander, suivre et recevoir vos documents officiels en toute sécurité.</p>
                <div class="flex space-x-4">
                    <a href="#" class="text-white hover:text-primary-400 transition-colors duration-300" title="Facebook">
                        <i class="fab fa-facebook-f text-xl"></i>
                    </a>
                    <a href="#" class="text-white hover:text-primary-400 transition-colors duration-300" title="Twitter">
                        <i class="fab fa-twitter text-xl"></i>
                    </a>
                    <a href="#" class="text-white hover:text-primary-400 transition-colors duration-300" title="Instagram">
                        <i class="fab fa-instagram text-xl"></i>
                    </a>
                    <a href="#" class="text-white hover:text-primary-400 transition-colors duration-300" title="LinkedIn">
                        <i class="fab fa-linkedin-in text-xl"></i>
                    </a>
                </div>
            </div>
            
            <!-- Liens rapides -->
            <div>
                <h6 class="text-lg font-semibold mb-4 text-primary-400">Liens rapides</h6>
                <ul class="space-y-3">
                    <li>
                        <a href="{{ url('/') }}" class="text-gray-300 hover:text-white transition-colors duration-300 flex items-center">
                            <i class="fas fa-chevron-right mr-2 text-xs text-primary-400"></i>Accueil
                        </a>
                    </li>
                    <li>
                        <a href="#" class="text-gray-300 hover:text-white transition-colors duration-300 flex items-center">
                            <i class="fas fa-chevron-right mr-2 text-xs text-primary-400"></i>À propos
                        </a>
                    </li>
                    <li>
                        <a href="#" class="text-gray-300 hover:text-white transition-colors duration-300 flex items-center">
                            <i class="fas fa-chevron-right mr-2 text-xs text-primary-400"></i>FAQ
                        </a>
                    </li>
                    <li>
                        <a href="#" class="text-gray-300 hover:text-white transition-colors duration-300 flex items-center">
                            <i class="fas fa-chevron-right mr-2 text-xs text-primary-400"></i>Nous contacter
                        </a>
                    </li>
                </ul>
            </div>
            
            <!-- Contact et assistance -->
            <div>
                <h6 class="text-lg font-semibold mb-4 text-primary-400">Contact et assistance</h6>
                <ul class="space-y-4">
                    <li class="flex items-start space-x-3">
                        <i class="fas fa-map-marker-alt text-primary-400 mt-1"></i>
                        <span class="text-gray-300">Avenue Chardy, Cocody<br>Abidjan, Côte d'Ivoire</span>
                    </li>
                    <li class="flex items-center space-x-3">
                        <i class="fas fa-envelope text-primary-400"></i>
                        <span class="text-gray-300">contact@actes-civils.ci</span>
                    </li>
                    <li class="flex items-center space-x-3">
                        <i class="fas fa-phone-alt text-primary-400"></i>
                        <span class="text-gray-300">+225 01 02 03 04 05</span>
                    </li>
                    <li class="flex items-center space-x-3">
                        <i class="fas fa-clock text-primary-400"></i>
                        <span class="text-gray-300">Lun-Ven: 8h-16h</span>
                    </li>
                </ul>
            </div>
        </div>
        
        <!-- Séparateur et copyright -->
        <div class="border-t border-gray-700 mt-8 pt-6">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                <p class="text-gray-300 text-center md:text-left">&copy; {{ date('Y') }} Plateforme d'Actes Civils. Tous droits réservés.</p>
                <div class="flex flex-wrap justify-center md:justify-end space-x-6 mt-4 md:mt-0">
                    <a href="#" class="text-gray-400 hover:text-white transition-colors duration-300 text-sm">Politique de confidentialité</a>
                    <a href="#" class="text-gray-400 hover:text-white transition-colors duration-300 text-sm">Conditions d'utilisation</a>
                    <a href="{{ route('admin.login') }}" class="text-gray-500 hover:text-gray-300 transition-colors duration-300 text-sm opacity-75">Administration</a>
                </div>
            </div>
        </div>
    </div>
</footer>
