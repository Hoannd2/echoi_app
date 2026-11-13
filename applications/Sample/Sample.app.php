<?php
	/* 1. New application object */
	$sampleApp = new SampleApp();
	
	/* 2. Check application ready to run */
	if ($sampleApp->readyRun(__FILE__, 1))
	{
		/* 3. Init application */
		$sampleApp->initialize();
		/* 4. Run application */
		$sampleApp->run();
		/* 5. Complete application */
		$sampleApp->finalize();
		
	}

?>