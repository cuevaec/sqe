<?php
/**
 * Created by PhpStorm.
 * User: manei
 * Date: 19/03/14
 * Time: 20:31
 */

class Jobtitles_Model extends CI_Model
{

    public function __construct()
    {
        $this->load->database();
    }


    public function getJobTitles()
    {
        $this->db->select();
        $this->db->from( 'job_titles as jt' );
        $this->db->join( 'sectors as s', 's.idSectors = jt.Sectors_idSectors' );
        $results = $this->db->get()->result_array();

        $objects = [ ];
        foreach ( $results as $result ) {
            $sector = new Sector( $result );
            $result['sector'] = $sector;
            $objects[] = new Jobtitle( $result );
        }

        return $objects;
    }

    public function getJobTitlesBySector()
    {
        $this->db->select();
        $this->db->from( 'job_titles as jt' );
        $this->db->join( 'sectors as s', 's.idSectors = jt.Sectors_idSectors' );
        $results = $this->db->get()->result_array();

        $objects = [ ];
        foreach ( $results as $result ) {
            $sector = new Sector( $result );
            $result['sector'] = $sector;
            $objects[$sector->getSectorTitle()][] = new Jobtitle( $result );
        }

        return $objects;
    }


    public function getJobTitle( $id )
    {
        $this->db->select();
        $this->db->from( 'job_titles as jt' );
        $this->db->join( 'sectors as s', 's.idSectors = jt.Sectors_idSectors' );
        $this->db->where( 'idJobTitles', $id );

        $result = $this->db->get()->row_array();
        $sector = new Sector( $result );
        $result['sector'] = $sector;

        return new Jobtitle( $result );
    }

    public function addJobTitle( Jobtitle $jobtitle )
    {
        $this->db->insert( 'job_titles', $jobtitle );
        return $this->db->insert_id();
    }

    public function searchJobTitles()
    {
        $this->db->select( '*' );
    }

    public function deleteJobTitle( $id )
    {
        $this->db->where( 'idJobTitles', $id );
        $this->db->delete( 'job_titles' );
    }

} 