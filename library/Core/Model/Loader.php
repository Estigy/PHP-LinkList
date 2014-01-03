<?php

/**
 * Enthält den Autoloader für die Model-Klassen.
 *  
 * @author Estigy
 */
class Core_Model_Loader
{
	/**
	 * Lädt die Model-Klassen. Diese beginnen mit "Model_" und liegen im Ordner application/models.
	 * @param string $className
	 */
	public static function autoload($className)
	{
		// Für alles, was nicht mit 'Model_' beginnt, sind wir nicht zuständig.
		if (strpos($className, 'Model_') !== 0) {
			return;
		}
		
		include str_replace('_', DIRECTORY_SEPARATOR, substr($className, 6)) . '.php';
		
		return class_exists($className, false);
	}
}