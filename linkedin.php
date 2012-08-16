<?php
/**
 * @package     Joomla.Platform
 * @subpackage  Linkedin
 *
 * @copyright   Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('JPATH_PLATFORM') or die();

/**
 * Joomla Platform class for interacting with a Linkedin API instance.
 *
 * @package     Joomla.Platform
 * @subpackage  Linkedin
 * @since       12.3
 */
class JLinkedin
{
	/**
	 * @var    JRegistry  Options for the Linkedin object.
	 * @since  12.3
	 */
	protected $options;

	/**
	 * @var    JHttp  The HTTP client object to use in sending HTTP requests.
	 * @since  12.3
	 */
	protected $client;

	/**
	 * @var    JLinkedinPeople  Linkedin API object for people.
	 * @since  12.3
	 */
	protected $people;

	/**
	 * @var    JLinkedinGroups  Linkedin API object for groups.
	 * @since  12.3
	 */
	protected $groups;

	/**
	 * @var    JLinkedinCompanies  Linkedin API object for companies.
	 * @since  12.3
	 */
	protected $companies;

	/**
	 * @var    JLinkedinJobs  Linkedin API object for jobs.
	 * @since  12.3
	 */
	protected $jobs;

	/**
	 * @var    JLinkedinStream  Linkedin API object for social stream.
	 * @since  12.3
	 */
	protected $stream;

	/**
	 * @var    JLinkedinCommunications  Linkedin API object for communications.
	 * @since  12.3
	 */
	protected $communications;

	/**
	 * Constructor.
	 *
	 * @param   JRegistry      $options  Linkedin options object.
	 * @param   JLinkedinHttp  $client   The HTTP client object.
	 *
	 * @since   12.3
	 */
	public function __construct(JRegistry $options = null, JHttp $client = null)
	{
		$this->options = isset($options) ? $options : new JRegistry;
		$this->client  = isset($client) ? $client : new JHttp($this->options);

		// Setup the default API url if not already set.
		$this->options->def('api.url', 'https://api.linkedin.com');
	}

	/**
	 * Magic method to lazily create API objects
	 *
	 * @param   string  $name  Name of property to retrieve
	 *
	 * @return  JLinkedinObject  Linkedin API object (statuses, users, favorites, etc.).
	 *
	 * @since   12.3
	 */
	public function __get($name)
	{
		switch ($name)
		{
			case 'people':
				if ($this->people == null)
				{
					$this->people = new JLinkedinPeople($this->options, $this->client);
				}
				return $this->people;

			case 'groups':
				if ($this->groups == null)
				{
					$this->groups = new JLinkedinGroups($this->options, $this->client);
				}
				return $this->groups;

			case 'companies':
				if ($this->companies == null)
				{
					$this->companies = new JLinkedinCompanies($this->options, $this->client);
				}
				return $this->companies;

			case 'jobs':
				if ($this->jobs == null)
				{
					$this->jobs = new JLinkedinJobs($this->options, $this->client);
				}
				return $this->jobs;

			case 'stream':
				if ($this->stream == null)
				{
					$this->stream = new JLinkedinStream($this->options, $this->client);
				}
				return $this->stream;

			case 'communications':
				if ($this->communications == null)
				{
					$this->communications = new JLinkedinCommunications($this->options, $this->client);
				}
				return $this->communications;
		}
	}

	/**
	 * Get an option from the JLinkedin instance.
	 *
	 * @param   string  $key  The name of the option to get.
	 *
	 * @return  mixed  The option value.
	 *
	 * @since   12.3
	 */
	public function getOption($key)
	{
		return $this->options->get($key);
	}

	/**
	 * Set an option for the Linkedin instance.
	 *
	 * @param   string  $key    The name of the option to set.
	 * @param   mixed   $value  The option value to set.
	 *
	 * @return  JLinkedin  This object for method chaining.
	 *
	 * @since   12.3
	 */
	public function setOption($key, $value)
	{
		$this->options->set($key, $value);

		return $this;
	}
}
