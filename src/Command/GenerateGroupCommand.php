<?php

namespace Drupal\metatag\Command;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Drupal\Console\Command\GeneratorCommand;
use Drupal\Console\Style\DrupalStyle;
use Drupal\metatag\Generator\MetatagGroupGenerator;

/**
 * Class GenerateGroupCommand.
 *
 * Generate a Metatag group plugin.
 *
 * @package Drupal\metatag
 */
class GenerateGroupCommand extends GeneratorCommand {

  /**
   * {@inheritdoc}
   */
  protected function configure() {
    $this
      ->setName('generate:metatag:group')
      ->setDescription($this->trans('commands.generate.metatag.group.description'))
      ->setHelp($this->trans('commands.generate.metatag.group.help'))
      ->addOption('base_class', '', InputOption::VALUE_REQUIRED,
        $this->trans('commands.common.options.base_class'))
      ->addOption('module', '', InputOption::VALUE_REQUIRED,
        $this->trans('commands.common.options.module'))
      ->addOption('label', '', InputOption::VALUE_REQUIRED,
        $this->trans('commands.generate.metatag.group.options.label'))
      ->addOption('description', '', InputOption::VALUE_OPTIONAL,
        $this->trans('commands.generate.metatag.group.options.description'))
      ->addOption('plugin-id', '', InputOption::VALUE_REQUIRED,
        $this->trans('commands.generate.metatag.group.options.plugin_id'))
      ->addOption('class-name', '', InputOption::VALUE_REQUIRED,
        $this->trans('commands.generate.metatag.group.options.class_name'))
      ->addOption('weight', '', InputOption::VALUE_REQUIRED,
        $this->trans('commands.generate.metatag.group.options.weight'));
  }

  /**
   * {@inheritdoc}
   */
  protected function execute(InputInterface $input, OutputInterface $output) {
    $io = new DrupalStyle($input, $output);

    if (!$this->confirmGeneration($io)) {
      return 1;
    }

    $base_class = $input->getOption('base_class');
    $module = $input->getOption('module');
    $label = $input->getOption('label');
    $description = $input->getOption('description');
    $plugin_id = $input->getOption('plugin-id');
    $class_name = $input->getOption('class-name');
    $weight = $input->getOption('weight');

    $this
      ->getGenerator()
      ->generate($base_class, $module, $label, $description, $plugin_id, $class_name, $weight);

    $this->getHelper('chain')->addCommand('cache:rebuild', ['cache' => 'discovery']);
  }

  /**
   * {@inheritdoc}
   */
  protected function interact(InputInterface $input, OutputInterface $output) {
    // --base_class option.
    // @todo Turn this into a choice() option.
    $base_class = $input->getOption('base_class');
    if (empty($base_class)) {
      $base_class = $output->ask(
        $this->trans('commands.generate.metatag.group.questions.base_class'),
        'GroupBase'
      );
    }
    $input->setOption('base_class', $base_class);

    // --module option.
    $module = $input->getOption('module');
    if (empty($module)) {
      $module = $this->moduleQuestion($output);
    }
    $input->setOption('module', $module);

    // --label option.
    $label = $input->getOption('label');
    if (empty($label)) {
      $label = $output->ask(
        $this->trans('commands.generate.metatag.group.questions.label')
      );
    }
    $input->setOption('label', $label);

    // --description option.
    $description = $input->getOption('description');
    if (empty($description)) {
      $description = $output->ask(
        $this->trans('commands.generate.metatag.group.questions.description')
      );
    }
    $input->setOption('description', $description);

    // --plugin-id option.
    $plugin_id = $input->getOption('plugin-id');
    if (empty($plugin_id)) {
      $plugin_id = $output->ask(
        $this->trans('commands.generate.metatag.group.questions.plugin_id')
      );
    }
    $input->setOption('plugin-id', $plugin_id);

    // --class-name option.
    $class_name = $input->getOption('class-name');
    if (empty($class_name)) {
      $class_name = $output->ask(
        $this->trans('commands.generate.metatag.group.questions.class_name')
      );
    }
    $input->setOption('class-name', $class_name);

    // --weight option.
    // @todo Automatically get the next integer value based upon the current
    //   group.
    $weight = $input->getOption('weight');
    if (is_null($weight)) {
      $weight = $output->ask(
        $this->trans('commands.generate.metatag.group.questions.weight'),
        0
      );
    }
    $input->setOption('weight', $weight);
  }

  /**
   * {@inheritdoc}
   */
  protected function createGenerator() {
    return new MetatagGroupGenerator();
  }

}
